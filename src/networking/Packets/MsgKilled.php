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
class MsgKilled extends GamePacket
{
    public const PACKET_TYPE = 'MsgKilled';

    /** @var int */
    private $victimId = -1;

    /** @var int */
    private $killerId = -1;

    /** @var int */
    private $reason = -1;

    /** @var int */
    private $shotId = -1;

    /** @var string */
    private $flag;

    /** @var int */
    private $physicsDriverId = -1;

    /**
     * @since 1.0.0
     */
    public function getVictimId(): int
    {
        return $this->victimId;
    }

    /**
     * @since 1.0.0
     */
    public function getKillerId(): int
    {
        return $this->killerId;
    }

    /**
     * @since 1.0.0
     */
    public function getReason(): int
    {
        return $this->reason;
    }

    /**
     * @since 1.0.0
     */
    public function getShotId(): int
    {
        return $this->shotId;
    }

    /**
     * @since 1.0.0
     */
    public function getFlag(): string
    {
        return $this->flag;
    }

    /**
     * @since 1.0.0
     */
    public function getPhysicsDriverId(): int
    {
        return $this->physicsDriverId;
    }

    /**
     * @since 1.0.0
     */
    protected function unpack(): void
    {
        $this->victimId = NetworkPacket::unpackUInt8($this->buffer);
        $this->killerId = NetworkPacket::unpackUInt8($this->buffer);
        $this->reason = NetworkPacket::unpackInt16($this->buffer);
        $this->shotId = NetworkPacket::unpackInt16($this->buffer);
        $this->flag = NetworkPacket::unpackString($this->buffer, 2);

        if ($this->reason === NetworkMessage::codeFromChars('pd'))
        {
            $this->physicsDriverId = NetworkPacket::unpackInt32($this->buffer);
        }
    }
}
