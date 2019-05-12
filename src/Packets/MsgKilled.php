<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

use allejo\bzflag\networking\GameData\FlagData;

class MsgKilled extends GamePacket
{
    const PACKET_TYPE = 'MsgKilled';

    /** @var int */
    private $victimId = -1;

    /** @var int */
    private $killerId = -1;

    /** @var int */
    private $reason = -1;

    /** @var int */
    private $shotId = -1;

    /** @var FlagData */
    private $flag;

    /** @var int */
    private $physicsDriverId = -1;

    protected function unpack(): void
    {
        $this->victimId = NetworkPacket::unpackUInt8($this->buffer);
        $this->killerId = NetworkPacket::unpackUInt8($this->buffer);
        $this->reason = NetworkPacket::unpackUInt16($this->buffer);
        $this->shotId = NetworkPacket::unpackUInt16($this->buffer);
        $this->flag = NetworkPacket::unpackString($this->buffer, 3);

        if ($this->reason === NetworkMessage::codeFromChars('pd'))
        {
            $this->physicsDriverId = NetworkPacket::unpackUInt32($this->buffer);
        }
    }
}
