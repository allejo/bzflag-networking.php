<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

use allejo\bzflag\networking\GameData\FlagData;

class MsgFlagUpdate extends GamePacket
{
    const PACKET_TYPE = 'MsgFlagUpdate';

    /** @var FlagData[] */
    private $flags;

    protected function unpack()
    {
        $count = NetworkPacket::unpackUInt16($this->buffer);

        for ($i = 0; $i < $count; ++$i)
        {
            $this->flags[] = NetworkPacket::unpackFlag($this->buffer);
        }
    }
}
