<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

class MsgAlive extends GamePacket
{
    const PACKET_TYPE = 'MsgAlive';

    /** @var int */
    private $playerId;

    /** @var float[] */
    private $position;

    /** @var float */
    private $azimuth;

    protected function defaultComplexVariables()
    {
        $this->position = [0, 0, 0];
    }

    protected function unpack()
    {
        $this->playerId = Packet::unpackUInt8($this->buffer);
        $this->position = Packet::unpackVector($this->buffer);
        $this->azimuth = Packet::unpackFloat($this->buffer);
    }
}
