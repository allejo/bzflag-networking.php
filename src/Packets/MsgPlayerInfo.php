<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

use allejo\bzflag\networking\GameData\PlayerData;

class MsgPlayerInfo extends GamePacket
{
    const PACKET_TYPE = 'MsgPlayerInfo';

    /** @var PlayerData[] */
    private $players;

    protected function unpack()
    {
        $count = Packet::unpackUInt8($this->buffer);

        for ($i = 0; $i < $count; ++$i)
        {
            $player = new PlayerData();
            $player->playerId = Packet::unpackUInt8($this->buffer);

            $properties = Packet::unpackUInt8($this->buffer);
            $player->isRegistered = (bool)($properties & PlayerData::IsRegistered);
            $player->isVerified = (bool)($properties & PlayerData::IsVerified);
            $player->isAdmin = (bool)($properties & PlayerData::IsAdmin);

            $this->players[] = $player;
        }
    }
}
