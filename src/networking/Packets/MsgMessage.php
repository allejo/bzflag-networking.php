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
class MsgMessage extends GamePacket
{
    public const PACKET_TYPE = 'MsgMessage';

    /** @var int */
    private $playerFromId;

    /** @var int */
    private $playerToId;

    /** @var string */
    private $message;

    /**
     * @since 1.0.0
     */
    public function getPlayerFromId(): int
    {
        return $this->playerFromId;
    }

    /**
     * @since 1.0.0
     */
    public function getPlayerToId(): int
    {
        return $this->playerToId;
    }

    /**
     * @since 1.0.0
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @since 1.0.0
     */
    protected function unpack(): void
    {
        $this->playerFromId = NetworkPacket::unpackUInt8($this->buffer);
        $this->playerToId = NetworkPacket::unpackUInt8($this->buffer);
        $this->message = NetworkPacket::unpackString($this->buffer, -1);
    }
}
