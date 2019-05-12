<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking;

use allejo\bzflag\networking\Packets\GamePacket;
use allejo\bzflag\networking\Packets\Packet;
use allejo\bzflag\networking\Packets\PacketInvalidException;
use allejo\bzflag\networking\Packets\UnsupportedPacket;

/**
 * @api
 */
class Replay implements \JsonSerializable
{
    private $header;
    private $packets;
    private $errors;
    private $startTime;
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
     * Specify data which should be serialized to JSON.
     *
     * @see https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @since 5.4.0
     *
     * @return mixed data which can be serialized by <b>json_encode</b>,
     *               which is a value of any type other than a resource
     */
    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
    }

    /**
     * @param resource $resource
     *
     * @throws PacketInvalidException
     */
    private function calculateTimestamps($resource): void
    {
        $packet = new Packet($resource);

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
            catch (UnsupportedPacket $e)
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
