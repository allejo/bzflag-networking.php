<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

/**
 * @since 1.0.0
 */
class MsgTeleport extends GamePacket
{
    public const PACKET_TYPE = 'MsgTeleport';

    /** @var int */
    private $playerId;

    /** @var int */
    private $from;

    /** @var int */
    private $to;

    /**
     * @since 1.0.0
     */
    public function getPlayerId(): int
    {
        return $this->playerId;
    }

    /**
     * @since 1.0.0
     */
    public function getFrom(): int
    {
        return $this->from;
    }

    /**
     * @since 1.0.0
     */
    public function getTo(): int
    {
        return $this->to;
    }

    /**
     * @since 1.0.0
     */
    protected function unpack(): void
    {
        $this->playerId = NetworkPacket::unpackUInt8($this->buffer);
        $this->from = NetworkPacket::unpackUInt16($this->buffer);
        $this->to = NetworkPacket::unpackUInt16($this->buffer);
    }
}
