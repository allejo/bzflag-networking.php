<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\GameData;

class PlayerScore implements \JsonSerializable
{
    /** @var int */
    public $wins;

    /** @var int */
    public $losses;

    /** @var int */
    public $teamKills;

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'wins' => $this->wins,
            'losses' => $this->losses,
            'teamKills' => $this->teamKills,
        ];
    }
}
