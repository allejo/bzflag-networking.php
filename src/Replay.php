<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking;

use allejo\bzflag\networking\Packets\GamePacket;
use allejo\bzflag\networking\Packets\NetworkPacket;
use allejo\bzflag\networking\Packets\PacketInvalidException;
use allejo\bzflag\networking\Packets\UnsupportedPacketException;

class Replay implements \JsonSerializable
{
    /** @var ReplayHeader */
    private $header;

    /** @var GamePacket[] */
    private $packets;

    /** @var string[] */
    private $errors;

    /** @var \DateTime */
    private $startTime;

    /** @var \DateTime */
    private $endTime;

    /** @var bool|resource */
    private $resource;

    /** @var bool Whether or not we've reached the end of this resource. */
    private $resourceClosed;

    /** @var int The location of where in the buffer game packets start */
    private $packetLocationStart;

    /**
     * Replay constructor.
     *
     * @param string $file
     *
     * @throws PacketInvalidException
     */
    public function __construct(string $file)
    {
        $this->resource = fopen($file, 'rb');
        $this->resourceClosed = false;
        $this->header = new ReplayHeader($this->resource);

        $this->calculateTimestamps($this->resource);

        $this->packetLocationStart = ftell($this->resource);
    }

    public function __destruct()
    {
        fclose($this->resource);
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return [
            'header' => $this->header,
            'startTime' => $this->startTime->format(DATE_ATOM),
            'endTime' => $this->endTime->format(DATE_ATOM),
            'packets' => $this->getPackets(),
        ];
    }

    /**
     * @return ReplayHeader
     */
    public function getHeader(): ReplayHeader
    {
        return $this->header;
    }

    /**
     * Get all of the packets in this Replay as an array.
     *
     * @return GamePacket[]
     */
    public function getPacketsAsArray(): array
    {
        if (count($this->packets) > 0)
        {
            return $this->packets;
        }

        $this->packets = [];

        foreach ($this->getPacketsIterable() as $packet)
        {
            if ($packet === null)
            {
                continue;
            }

            $this->packets[] = $packet;
        }

        return $this->packets;
    }

    /**
     * Get all of the packets in this Replay as an array.
     *
     * @deprecated use `Replay::getPacketsAsArray()` instead
     *
     * @return GamePacket[]
     */
    public function getPackets(): array
    {
        return $this->getPacketsAsArray();
    }

    /**
     * Iterate through all of the packets in this Replay one at a time without
     * saving everything in memory.
     *
     * @return iterable
     */
    public function getPacketsIterable(): iterable
    {
        if ($this->resourceClosed)
        {
            fseek($this->resource, $this->packetLocationStart);
        }

        while (true)
        {
            try
            {
                yield GamePacket::fromResource($this->resource);
            }
            catch (UnsupportedPacketException $e)
            {
                $this->errors = $e->getMessage();
            }
            catch (PacketInvalidException $e)
            {
                $this->resourceClosed = true;

                break;
            }
        }
    }

    /**
     * @return string[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return \DateTime
     */
    public function getStartTime(): \DateTime
    {
        return $this->startTime;
    }

    /**
     * @return \DateTime
     */
    public function getEndTime(): \DateTime
    {
        return $this->endTime;
    }

    /**
     * @param resource $resource
     *
     * @throws PacketInvalidException
     */
    private function calculateTimestamps($resource): void
    {
        $packet = new NetworkPacket($resource);

        if ($packet->getTimestamp() === null)
        {
            throw new PacketInvalidException();
        }

        $this->startTime = $packet->getTimestamp();

        $replayDuration = sprintf('+%d seconds', $this->header->getFileTimeAsSeconds());
        $this->endTime = $packet->getTimestamp()->modify($replayDuration);
    }
}
