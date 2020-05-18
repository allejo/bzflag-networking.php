<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\World;

use allejo\bzflag\networking\Packets\NetworkPacket;

class PhysicsDriver
{
    /** @var string */
    public $name;

    /** @var array<int, float> */
    public $linear;

    /** @var float */
    public $angularVel;

    /** @var array<int, float> */
    public $angularPos;

    /** @var float */
    public $radialVel;

    /** @var array<int, float> */
    public $radialPos;

    /** @var float */
    public $slideTime;

    /** @var string */
    public $deathMessage;

    public function __construct()
    {
        $this->angularPos = [];
        $this->radialPos = [];
    }

    public function unpack($resource): void
    {
        $this->name = NetworkPacket::unpackStdString($resource);

        $this->linear = NetworkPacket::unpackVector($resource);
        $this->angularVel = NetworkPacket::unpackFloat($resource);
        $this->angularPos[0] = NetworkPacket::unpackFloat($resource);
        $this->angularPos[1] = NetworkPacket::unpackFloat($resource);
        $this->radialVel = NetworkPacket::unpackFloat($resource);
        $this->radialPos[0] = NetworkPacket::unpackFloat($resource);
        $this->radialPos[1] = NetworkPacket::unpackFloat($resource);

        $this->slideTime = NetworkPacket::unpackFloat($resource);
        $this->deathMessage = NetworkPacket::unpackStdString($resource);
    }
}
