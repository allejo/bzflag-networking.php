<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

class MsgShotEnd extends GamePacket
{
    public const PACKET_TYPE = 'MsgShotEnd';

    /** @var int */
    private $playerId;

    /** @var int */
    private $shotId;

    /** @var int */
    private $reason;

    protected function unpack()
    {
        $this->playerId = NetworkPacket::unpackUInt8($this->buffer);
        $this->shotId = NetworkPacket::unpackUInt16($this->buffer);
        $this->reason = NetworkPacket::unpackInt16($this->buffer);
    }
}
