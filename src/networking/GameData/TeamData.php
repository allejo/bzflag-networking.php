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
class TeamData implements \JsonSerializable
{
    /** @var int */
    public $team;

    /** @var int */
    public $size;

    /** @var int */
    public $wins;

    /** @var int */
    public $losses;

    /**
     * @since 1.0.0
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'team' => $this->team,
            'size' => $this->size,
            'wins' => $this->wins,
            'losses' => $this->losses,
        ];
    }
}
