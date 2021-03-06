<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\GameData;

/**
 * @since 1.0.0
 */
class ScoreData implements \JsonSerializable
{
    /** @var int */
    public $playerId;

    /** @var int */
    public $wins;

    /** @var int */
    public $losses;

    /** @var int */
    public $teamKills;

    /**
     * @since 1.0.0
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'playerId' => $this->playerId,
            'wins' => $this->wins,
            'losses' => $this->losses,
            'teamKills' => $this->teamKills,
        ];
    }
}
