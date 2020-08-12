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
class MsgNewRabbit extends GamePacket
{
    public const PACKET_TYPE = 'MsgNewRabbit';

    /** @var int */
    private $playerId;

    /** @var int */
    private $paused;

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
    public function getPaused(): int
    {
        return $this->paused;
    }

    /**
     * @since 1.0.0
     */
    protected function unpack(): void
    {
        $this->playerId = NetworkPacket::unpackUInt8($this->buffer);
        $this->paused = NetworkPacket::unpackUInt8($this->buffer);
    }
}
