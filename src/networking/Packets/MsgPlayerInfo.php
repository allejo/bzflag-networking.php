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
    public const PACKET_TYPE = 'MsgPlayerInfo';

    /** @var PlayerData[] */
    private $players;

    /**
     * @return PlayerData[]
     */
    public function getPlayers(): array
    {
        return $this->players;
    }

    protected function unpack(): void
    {
        $count = NetworkPacket::unpackUInt8($this->buffer);

        for ($i = 0; $i < $count; ++$i)
        {
            $player = new PlayerData();
            $player->playerId = NetworkPacket::unpackUInt8($this->buffer);

            $properties = NetworkPacket::unpackUInt8($this->buffer);
            $player->isRegistered = (bool)($properties & PlayerData::IS_REGISTERED);
            $player->isVerified = (bool)($properties & PlayerData::IS_VERIFIED);
            $player->isAdmin = (bool)($properties & PlayerData::IS_ADMIN);

            $this->players[] = $player;
        }
    }
}
