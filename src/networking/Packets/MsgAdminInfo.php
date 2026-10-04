<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

use allejo\bzflag\networking\GameData\PlayerInfo;

/**
 * @since 1.0.0
 */
class MsgAdminInfo extends GamePacket
{
    public const PACKET_TYPE = 'MsgAdminInfo';

    /** @var PlayerInfo[] */
    private $players = [];

    /**
     * @since 1.0.0
     *
     * @return PlayerInfo[]
     */
    public function getPlayers(): array
    {
        return $this->players;
    }

    /**
     * @since future
     */
    public function pack(): string
    {
        $packed = NetworkPacket::packUInt8(count($this->players));

        foreach ($this->players as $playerInfo)
        {
            // Size of this player's entry, as BZFS sends it
            $packed .= NetworkPacket::packUInt8(5)
                . NetworkPacket::packUInt8($playerInfo->playerIndex)
                . NetworkPacket::packIpAddress($playerInfo->ipAddress);
        }

        return $packed;
    }

    /**
     * @since 1.0.0
     */
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
