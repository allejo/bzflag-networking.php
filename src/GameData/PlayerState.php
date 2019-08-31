<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\GameData;

class PlayerState implements \JsonSerializable
{
    const DEAD_STATUS = 0;        // not live, not paused, etc.
    const ALIVE = 1 << 0;         // player is alive
    const PAUSED = 1 << 1;        // player is paused
    const EXPLODING = 1 << 2;     // currently blowing up
    const TELEPORTING = 1 << 3;   // teleported recently
    const FLAG_ACTIVE = 1 << 4;   // flag special powers active
    const CROSSING_WALL = 1 << 5; // tank crossing building wall
    const FALLING = 1 << 6;       // tank accel'd by gravity
    const ON_DRIVER = 1 << 7;     // tank is on a physics driver
    const USER_INPUTS = 1 << 8;   // user speed and angvel are sent
    const JUMP_JETS = 1 << 9;     // tank has jump jets on
    const PLAY_SOUND = 1 << 10;   // play one or more sounds

    const NO_SOUNDS = 0;
    const JUMP_SOUNDS = 1 << 0;
    const WINGS_SOUND = 1 << 1;
    const BOUNCE_SOUND = 1 << 2;

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

    public function jsonSerialize()
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
