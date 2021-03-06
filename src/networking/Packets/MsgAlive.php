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
class MsgAlive extends GamePacket
{
    public const PACKET_TYPE = 'MsgAlive';

    /** @var int */
    private $playerId;

    /** @var float[] */
    private $position;

    /** @var float */
    private $azimuth;

    /**
     * @since 1.0.0
     */
    public function getPlayerId(): int
    {
        return $this->playerId;
    }

    /**
     * @since 1.0.0
     *
     * @return float[]
     */
    public function getPosition(): array
    {
        return $this->position;
    }

    /**
     * @since 1.0.0
     */
    public function getAzimuth(): float
    {
        return $this->azimuth;
    }

    /**
     * @since 1.0.0
     */
    protected function defaultComplexVariables(): void
    {
        $this->position = [0, 0, 0];
    }

    /**
     * @since 1.0.0
     */
    protected function unpack(): void
    {
        $this->playerId = NetworkPacket::unpackUInt8($this->buffer);
        $this->position = NetworkPacket::unpackVector($this->buffer);
        $this->azimuth = NetworkPacket::unpackFloat($this->buffer);
    }
}
