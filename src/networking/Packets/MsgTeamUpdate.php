<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

use allejo\bzflag\networking\GameData\TeamData;
use allejo\bzflag\networking\InaccessibleResourceException;

class MsgTeamUpdate extends GamePacket
{
    public const PACKET_TYPE = 'MsgTeamUpdate';

    /** @var TeamData[] */
    private $teams;

    /**
     * @return TeamData[]
     */
    public function getTeams(): array
    {
        return $this->teams;
    }

    /**
     * {@inheritdoc}
     *
     * @throws InaccessibleResourceException
     */
    protected function unpack(): void
    {
        $count = NetworkPacket::unpackUInt8($this->buffer);

        for ($i = 0; $i < $count; ++$i)
        {
            $data = new TeamData();

            $data->team = NetworkPacket::unpackUInt16($this->buffer);
            $data->size = NetworkPacket::unpackUInt16($this->buffer);
            $data->wins = NetworkPacket::unpackUInt16($this->buffer);
            $data->losses = NetworkPacket::unpackUInt16($this->buffer);

            $this->teams[] = $data;
        }
    }
}
