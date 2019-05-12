<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

class MsgFlagUpdate extends GamePacket
{
    const PACKET_TYPE = 'MsgFlagUpdate';

    /** @var GameDataFlagData[] */
    private $flags;

    protected function unpack()
    {
        $count = Packet::unpackUInt16($this->buffer);

        for ($i = 0; $i < $count; ++$i)
        {
            $this->flags[] = Packet::unpackFlag($this->buffer);
        }
    }
}
