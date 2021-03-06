<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

use allejo\bzflag\networking\GameData\FlagData;

/**
 * @since 1.0.0
 */
class MsgTransferFlag extends GamePacket
{
    public const PACKET_TYPE = 'MsgTransferFlag';

    /** @var int */
    private $from;

    /** @var int */
    private $to;

    /** @var FlagData */
    private $flag;

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
    public function getFlag(): FlagData
    {
        return $this->flag;
    }

    /**
     * @since 1.0.0
     */
    protected function unpack(): void
    {
        $this->from = NetworkPacket::unpackUInt8($this->buffer);
        $this->to = NetworkPacket::unpackUInt8($this->buffer);
        $this->flag = NetworkPacket::unpackFlag($this->buffer);
    }
}
