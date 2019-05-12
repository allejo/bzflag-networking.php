<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

class MsgTeleport extends GamePacket
{
    public const PACKET_TYPE = 'MsgTeleport';

    /** @var int */
    private $playerId;

    /** @var int */
    private $from;

    /** @var int */
    private $to;

    protected function unpack()
    {
        $this->playerId = Packet::unpackUInt8($this->buffer);
        $this->from = Packet::unpackUInt16($this->buffer);
        $this->to = Packet::unpackUInt16($this->buffer);
    }
}
