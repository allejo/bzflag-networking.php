<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

use allejo\bzflag\networking\GameData\FlagData;

class MsgTransferFlag extends GamePacket
{
    public const PACKET_TYPE = 'MsgTransferFlag';

    /** @var int */
    private $from;

    /** @var int */
    private $to;

    /** @var FlagData */
    private $flag;

    protected function unpack()
    {
        $this->from = Packet::unpackUInt8($this->buffer);
        $this->to = Packet::unpackUInt8($this->buffer);
        $this->flag = Packet::unpackFlag($this->buffer);
    }
}
