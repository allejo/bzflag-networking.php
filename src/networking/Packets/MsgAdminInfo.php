<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

use allejo\bzflag\networking\GameData\PlayerInfo;

class MsgAdminInfo extends GamePacket
{
    public const PACKET_TYPE = 'MsgAdminInfo';

    /** @var PlayerInfo[] */
    private $players = [];

    /**
     * @return PlayerInfo[]
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
            NetworkPacket::unpackUInt8($this->buffer);

            $playerInfo = new PlayerInfo();
            $playerInfo->playerIndex = NetworkPacket::unpackUInt8($this->buffer);
            $playerInfo->ipAddress = NetworkPacket::unpackIpAddress($this->buffer);

            $this->players[] = $playerInfo;
        }
    }
}
