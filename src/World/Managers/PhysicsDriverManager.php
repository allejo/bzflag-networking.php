<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\World\Managers;

use allejo\bzflag\networking\Packets\NetworkPacket;
use allejo\bzflag\networking\World\Modifiers\PhysicsDriver;

class PhysicsDriverManager
{
    /** @var array<int, PhysicsDriver> */
    private $physicsDriver;

    public function __construct()
    {
        $this->physicsDriver = [];
    }

    /**
     * @return array<int, PhysicsDriver>
     */
    public function getPhysicsDriver(): array
    {
        return $this->physicsDriver;
    }

    public function unpack(&$resource): void
    {
        $count = NetworkPacket::unpackUInt32($resource);

        for ($i = 0; $i < $count; ++$i)
        {
            $physicsDriver = new PhysicsDriver();
            $physicsDriver->unpack($resource);

            $this->physicsDriver[] = $physicsDriver;
        }
    }
}
