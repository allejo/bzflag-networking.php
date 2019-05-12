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
    const DeadStatus = 0;        // not live, not paused, etc.
    const Alive = 1 << 0;        // player is alive
    const Paused = 1 << 1;       // player is paused
    const Exploding = 1 << 2;    // currently blowing up
    const Teleporting = 1 << 3;  // teleported recently
    const FlagActive = 1 << 4;   // flag special powers active
    const CrossingWall = 1 << 5; // tank crossing building wall
    const Falling = 1 << 6;      // tank accel'd by gravity
    const OnDriver = 1 << 7;     // tank is on a physics driver
    const UserInputs = 1 << 8;   // user speed and angvel are sent
    const JumpJets = 1 << 9;     // tank has jump jets on
    const PlaySound = 1 << 10;   // play one or more sounds

    public $position;
    public $velocity;
    public $azimuth;
    public $angularVelocity;
    public $physicsDriver;
    public $userSpeed;
    public $userAngVel;
    public $jumpJetsScale;
    public $sounds;

    public function jsonSerialize()
    {
        return [
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
