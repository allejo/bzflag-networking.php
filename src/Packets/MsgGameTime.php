<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

class MsgGameTime extends GamePacket
{
    const PACKET_TYPE = 'MsgGameTime';

    /** @var int */
    private $msb;

    /** @var int */
    private $lsb;

    protected function unpack()
    {
        $this->msb = NetworkPacket::unpackUInt32($this->buffer);
        $this->lsb = NetworkPacket::unpackUInt32($this->buffer);
    }
}
