<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\GameData;

class ScoreData implements \JsonSerializable
{
    public $playerId;
    public $wins;
    public $losses;
    public $teamKills;

    public function jsonSerialize()
    {
        return [
            'playerId' => $this->playerId,
            'wins' => $this->wins,
            'losses' => $this->losses,
            'teamKills' => $this->teamKills,
        ];
    }
}
