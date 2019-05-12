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

    /**
     * Replay constructor.
     *
     * @param string $file
     *
     * @throws PacketInvalidException
     */
    public function __construct(string $file)
    {
        $resource = fopen($file, 'rb');

        $this->header = new ReplayHeader($resource);

        $this->calculateTimestamps($resource);
        $this->loadPackets($resource);

        fclose($resource);
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        $dateFormat = \DateTimeInterface::ATOM;

        return [
            'header' => $this->header,
            'startTime' => $this->startTime->format($dateFormat),
            'endTime' => $this->endTime->format($dateFormat),
            'packets' => $this->packets,
        ];
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

    /**
     * @param resource $resource
     */
    private function loadPackets($resource): void
    {
        while (true)
        {
            try
            {
                $this->packets[] = GamePacket::fromResource($resource);
            }
            catch (UnsupportedPacketException $e)
            {
                $this->errors = $e->getMessage();
            }
            catch (PacketInvalidException $e)
            {
                break;
            }
        }
    }
}
