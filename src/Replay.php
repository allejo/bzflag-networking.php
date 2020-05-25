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

    /** @var resource */
    private $resource;

    /** @var bool Whether or not we've reached the end of this resource. */
    private $resourceClosed;

    /** @var int The location of where in the buffer game packets start */
    private $packetLocationStart;

    /**
     * Replay constructor.
     *
     * @throws InvalidReplayException
     * @throws PacketInvalidException
     */
    public function __construct(string $file)
    {
        $resource = fopen($file, 'rb');

        if ($resource === false)
        {
            throw new \InvalidArgumentException("The replay file ({$file}) could not be read.");
        }

        $this->resource = $resource;
        $stats = fstat($this->resource);

        if ($stats['size'] == 0)
        {
            throw new InvalidReplayException("The replay file has a length of 0 ({$file})");
        }

        $this->resourceClosed = false;
        $this->header = new ReplayHeader($this->resource);
        $this->packets = [];

        $this->calculateTimestamps($this->resource);

        $startLocation = ftell($this->resource);

        if ($startLocation === false)
        {
            throw new InvalidReplayException('Could not determine the starting location of replay packets.');
        }

        $this->packetLocationStart = $startLocation;
    }

    public function __destruct()
    {
        fclose($this->resource);
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'header' => $this->header,
            'startTime' => $this->startTime->format(DATE_ATOM),
            'endTime' => $this->endTime->format(DATE_ATOM),
            'packets' => $this->getPacketsAsArray(),
        ];
    }

    public function getHeader(): ReplayHeader
    {
        return $this->header;
    }

    /**
     * Get all of the packets in this Replay as an array.
     *
     * **Warning:** This requires a higher amount of memory since all of these
     * packets will be stored in an array.
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
     * @return iterable<GamePacket>
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
                $this->errors[] = $e->getMessage();
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

    public function getStartTime(): \DateTime
    {
        return $this->startTime;
    }

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

        $this->startTime = $packet->getTimestamp();

        $replayDuration = sprintf('+%d seconds', $this->header->getFileTimeAsSeconds());
        $this->endTime = $packet->getTimestamp()->modify($replayDuration);
    }
}
