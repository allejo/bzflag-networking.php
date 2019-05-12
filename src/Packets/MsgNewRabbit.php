<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

class MsgNewRabbit extends GamePacket
{
    const PACKET_TYPE = 'MsgNewRabbit';

    /** @var int */
    private $playerId;

    /** @var int */
    private $paused;

    protected function unpack()
    {
        $this->playerId = Packet::unpackUInt8($this->buffer);
        $this->paused = Packet::unpackUInt8($this->buffer);
    }
}
