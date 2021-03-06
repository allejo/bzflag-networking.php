<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

use allejo\bzflag\networking\GameData\ScoreData;

/**
 * @since 1.0.0
 */
class MsgScore extends GamePacket
{
    public const PACKET_TYPE = 'MsgScore';

    /** @var ScoreData[] */
    private $scores = [];

    /**
     * @since 1.0.0
     *
     * @return ScoreData[]
     */
    public function getScores(): array
    {
        return $this->scores;
    }

    /**
     * @since 1.0.0
     */
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
