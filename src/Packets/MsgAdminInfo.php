<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

class MsgAdminInfo extends GamePacket
{
    const PACKET_TYPE = 'MsgAdminInfo';

    /** @var GameDataPlayerInfo[] */
    private $players = [];

    protected function unpack()
    {
        $count = Packet::unpackUInt8($this->buffer);

        for ($i = 0; $i < $count; ++$i)
        {
            Packet::unpackUInt8($this->buffer);

            $playerInfo = new GameDataPlayerInfo();
            $playerInfo->playerIndex = Packet::unpackUInt8($this->buffer);
            $playerInfo->ipAddress = Packet::unpackIpAddress($this->buffer);

            $this->players[] = $playerInfo;
        }
    }
}
