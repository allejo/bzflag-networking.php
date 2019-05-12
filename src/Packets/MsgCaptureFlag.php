<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

class MsgCaptureFlag extends GamePacket
{
    const PACKET_TYPE = 'MsgCaptureFlag';

    /** @var int */
    private $playerId;

    /** @var int */
    private $flagId;

    /** @var int */
    private $team;

    protected function unpack()
    {
        $this->playerId = Packet::unpackUInt8($this->buffer);
        $this->flagId = Packet::unpackUInt16($this->buffer);
        $this->team = Packet::unpackUInt16($this->buffer);
    }
}
