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
class ShotData implements \JsonSerializable
{
    /** @var int */
    public $playerId;

    /** @var int */
    public $shotId;

    /** @var float[] */
    public $position;

    /** @var float[] */
    public $velocity;

    /** @var float */
    public $deltaTime;

    /** @var int */
    public $team;

    /**
     * @since 1.0.0
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'playerId' => $this->playerId,
            'shotId' => $this->shotId,
            'position' => $this->position,
            'velocity' => $this->velocity,
            'deltaTime' => $this->deltaTime,
            'team' => $this->team,
        ];
    }
}
