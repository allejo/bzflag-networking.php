<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

class MsgTimeUpdate extends GamePacket
{
    public const PACKET_TYPE = 'MsgTimeUpdate';

    /** @var int */
    private $timeLeft;

    protected function unpack()
    {
        $this->timeLeft = Packet::unpackInt32($this->buffer);
    }
}
