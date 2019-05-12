<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

use allejo\bzflag\networking\GameData\ScoreData;

class MsgScore extends GamePacket
{
    public const PACKET_TYPE = 'MsgScore';

    /** @var array ScoreData[] */
    private $scores = [];

    /**
     * @return array
     */
    public function getScores(): array
    {
        return $this->scores;
    }

    protected function unpack(): void
    {
        $count = NetworkPacket::unpackUInt8($this->buffer);

        for ($i = 0; $i < $count; ++$i)
        {
            $score = new ScoreData();

            $score->playerId = NetworkPacket::unpackUInt8($this->buffer);
            $score->wins = NetworkPacket::unpackUInt16($this->buffer);
            $score->losses = NetworkPacket::unpackUInt16($this->buffer);
            $score->teamKills = NetworkPacket::unpackUInt16($this->buffer);

            $this->scores[] = $score;
        }
    }
}
