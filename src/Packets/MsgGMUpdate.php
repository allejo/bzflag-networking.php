<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

use allejo\bzflag\networking\GameData\ShotData;

class MsgGMUpdate extends GamePacket
{
    const PACKET_TYPE = 'MsgGMUpdate';

    /** @var int */
    private $target;

    /** @var ShotData */
    private $shot;

    protected function unpack(): void
    {
        $this->target = NetworkPacket::unpackUInt8($this->buffer);
        $this->shot = NetworkPacket::unpackShot($this->buffer);
    }
}
