<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\replays;

use allejo\bzflag\networking\Exceptions\InaccessibleResourceException;
use allejo\bzflag\networking\Exceptions\InvalidTimestampFormatException;
use allejo\bzflag\networking\Packets\GamePacket;
use allejo\bzflag\networking\Packets\MsgSetVar;
use allejo\bzflag\networking\Packets\NetworkPacket;
use allejo\bzflag\networking\Packets\PacketInvalidException;
use allejo\bzflag\networking\Packets\UnsupportedPacketException;
use allejo\bzflag\replays\Exceptions\InvalidReplayException;
use allejo\bzflag\world\Exceptions\InvalidWorldCompressionException;
use allejo\bzflag\world\Exceptions\InvalidWorldDatabaseException;
use allejo\bzflag\world\WorldDatabase;

/**
 * @since 1.1.0 This class moved namespaces to `allejo\bzflag\replays`
 * @since 1.0.0
 */
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
     * @since 1.0.0
     *
     * @throws InaccessibleResourceException
     * @throws InvalidReplayException
     * @throws InvalidTimestampFormatException
     * @throws InvalidWorldCompressionException
     * @throws InvalidWorldDatabaseException
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

        if ($stats === false)
        {
            throw new InvalidReplayException("Could not fstat() on this replay ({$file})");
        }

        if ($stats['size'] === 0)
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
        $this->attachBZDB();
    }

    /**
     * @since 1.0.1
     */
    public function __destruct()
    {
        fclose($this->resource);
    }

    /**
     * @since 1.0.0
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        try
        {
            $packets = $this->getPacketsIterable();
        }
        catch (InaccessibleResourceException|InvalidTimestampFormatException $e)
        {
            $packets = [
                '_error' => [
                    sprintf('Replay Unpacking Error: Could not unpack replay packet.'),
                    sprintf('  %s', $e->getMessage()),
                ],
            ];
        }

        return [
            'header' => $this->header,
            'startTime' => $this->startTime->format(DATE_ATOM),
            'endTime' => $this->endTime->format(DATE_ATOM),
            'packets' => $packets,
        ];
    }

    /**
     * @since 1.0.0
     */
    public function getHeader(): ReplayHeader
    {
        return $this->header;
    }

    /**
     * @since future
     */
    public function getWorldDatabase(): WorldDatabase
    {
        return $this->header->getWorldDatabase();
    }

    /**
     * Get all of the packets in this Replay as an array.
     *
     * **Warning:** This requires a higher amount of memory since all of these
     * packets will be stored in an array.
     *
     * @since 1.0.1
     *
     * @throws InaccessibleResourceException
     * @throws InvalidTimestampFormatException
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
     * @deprecated 1.0.1 use `Replay::getPacketsAsArray()` instead
     * @since      1.0.0
     *
     * @throws InaccessibleResourceException
     * @throws InvalidTimestampFormatException
     *
     * @return GamePacket[]
     *
     * @since future
     */
    public function getPackets(): array
    {
        trigger_deprecation('allejo/bzflag-networking.php', '1.0.1', 'Using "%s" is deprecated, use "%s" instead.', 'getPackets', 'getPacketsAsArray');

        return $this->getPacketsAsArray();
    }

    /**
     * Iterate through all of the packets in this Replay one at a time without
     * saving everything in memory.
     *
     * @since 1.0.1
     *
     * @throws InaccessibleResourceException
     * @throws InvalidTimestampFormatException
     *
     * @return iterable<GamePacket>
     */
    public function getPacketsIterable(): iterable
    {
        if ($this->resourceClosed)
        {
            $this->resetPacketsIterator();
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
     * @since 1.0.0
     *
     * @return string[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @since 1.0.0
     */
    public function getStartTime(): \DateTime
    {
        return $this->startTime;
    }

    /**
     * @since 1.0.0
     */
    public function getEndTime(): \DateTime
    {
        return $this->endTime;
    }

    /**
     * Reset the iterator used for `getPacketsIterable()`.
     *
     * @since 1.1.0
     */
    public function resetPacketsIterator(): void
    {
        fseek($this->resource, $this->packetLocationStart);
    }

    /**
     * @since 1.0.0
     *
     * @param resource $resource
     *
     * @throws InaccessibleResourceException
     * @throws InvalidTimestampFormatException
     * @throws PacketInvalidException
     */
    private function calculateTimestamps($resource): void
    {
        $packet = new NetworkPacket($resource);

        $this->startTime = $packet->getTimestamp();

        $replayDuration = sprintf('+%d seconds', $this->header->getFileTimeAsSeconds());
        $this->endTime = $packet->getTimestamp()->modify($replayDuration);
    }

    /**
     * Attach only the *initial* BZDB settings to the WorldDatabase.
     *
     * @since 1.1.0
     *
     * @throws InaccessibleResourceException
     * @throws InvalidTimestampFormatException
     */
    private function attachBZDB(): void
    {
        foreach ($this->getPacketsIterable() as $packet)
        {
            // In a replay, the MsgSetVar packets are sent first, so as soon as
            // we hit a non-MsgSetVar packet that means we're done
            if (!($packet instanceof MsgSetVar))
            {
                break;
            }

            $this
                ->getHeader()
                ->getWorldDatabase()
                ->getBZDBManager()
                ->unpackFromMsgSetVar($packet)
            ;
        }

        $this->resetPacketsIterator();
    }
}
