<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

use allejo\bzflag\networking\GameData\FiringIntoData;

class MsgShotBegin extends GamePacket
{
    public const PACKET_TYPE = 'MsgShotBegin';

    /** @var FiringIntoData */
    private $firingInfo;

    protected function unpack()
    {
        $this->firingInfo = Packet::unpackFiringInfo($this->buffer);
    }
}
