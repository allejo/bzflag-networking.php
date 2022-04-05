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
class PlayerState implements \JsonSerializable
{
    public const DEAD_STATUS = 0;        // not live, not paused, etc.
    public const ALIVE = 1 << 0;         // player is alive
    public const PAUSED = 1 << 1;        // player is paused
    public const EXPLODING = 1 << 2;     // currently blowing up
    public const TELEPORTING = 1 << 3;   // teleported recently
    public const FLAG_ACTIVE = 1 << 4;   // flag special powers active
    public const CROSSING_WALL = 1 << 5; // tank crossing building wall
    public const FALLING = 1 << 6;       // tank accel'd by gravity
    public const ON_DRIVER = 1 << 7;     // tank is on a physics driver
    public const USER_INPUTS = 1 << 8;   // user speed and angvel are sent
    public const JUMP_JETS = 1 << 9;     // tank has jump jets on
    public const PLAY_SOUND = 1 << 10;   // play one or more sounds

    public const NO_SOUNDS = 0;
    public const JUMP_SOUNDS = 1 << 0;
    public const WINGS_SOUND = 1 << 1;
    public const BOUNCE_SOUND = 1 << 2;

    /** @var int */
    public $status;

    /** @var int */
    public $order;

    /** @var float[] */
    public $position;

    /** @var float[] */
    public $velocity;

    /** @var float */
    public $azimuth;

    /** @var float */
    public $angularVelocity;

    /** @var int */
    public $physicsDriver;

    /** @var float */
    public $userSpeed;

    /** @var float */
    public $userAngVel;

    /** @var float */
    public $jumpJetsScale;

    /** @var int */
    public $sounds;

    /**
     * @since 1.0.0
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'order' => $this->order,
            'status' => $this->status,
            'position' => $this->position,
            'velocity' => $this->velocity,
            'azimuth' => $this->azimuth,
            'angularVelocity' => $this->angularVelocity,
            'physicsDriver' => $this->physicsDriver,
            'userSpeed' => $this->userSpeed,
            'userAng_Vel' => $this->userAngVel,
            'jumpJetsScale' => $this->jumpJetsScale,
            'sounds' => $this->sounds,
        ];
    }
}
