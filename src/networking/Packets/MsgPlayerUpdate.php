<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

use allejo\bzflag\networking\GameData\PlayerState;

/**
 * @since 1.0.0
 */
class MsgPlayerUpdate extends GamePacket
{
    public const PACKET_TYPE = 'MsgPlayerUpdate';

    /** @var int */
    private $playerId;

    /** @var PlayerState */
    private $state;

    /** @var float Kept only so `pack()` can write it back */
    private $playerTimestamp;

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
    public function getState(): PlayerState
    {
        return $this->state;
    }

    /**
     * @since future
     */
    public function pack(): string
    {
        return NetworkPacket::packFloat($this->playerTimestamp)
            . NetworkPacket::packUInt8($this->playerId)
            . NetworkPacket::packPlayerState($this->state, $this->packet->getCode());
    }

    /**
     * @since 1.0.0
     */
    protected function unpack(): void
    {
        // Discard this value; I'm not sure why this value comes out to a weird
        // float. We have the timestamp of the raw packet, so just that instead
        $this->playerTimestamp = NetworkPacket::unpackFloat($this->buffer);

        $this->playerId = NetworkPacket::unpackUInt8($this->buffer);
        $this->state = NetworkPacket::unpackPlayerState($this->buffer, $this->packet->getCode());
    }
}
