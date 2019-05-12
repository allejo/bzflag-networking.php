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

    protected function unpack()
    {
        $count = Packet::unpackUInt8($this->buffer);

        for ($i = 0; $i < $count; ++$i)
        {
            $score = new ScoreData();

            $score->playerId = Packet::unpackUInt8($this->buffer);
            $score->wins = Packet::unpackUInt16($this->buffer);
            $score->losses = Packet::unpackUInt16($this->buffer);
            $score->teamKills = Packet::unpackUInt16($this->buffer);

            $this->scores[] = $score;
        }
    }
}
