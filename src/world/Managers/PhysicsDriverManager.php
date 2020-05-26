<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\world\Managers;

use allejo\bzflag\networking\Packets\NetworkPacket;
use allejo\bzflag\world\Modifiers\PhysicsDriver;

class PhysicsDriverManager extends BaseManager
{
    /** @var array<int, PhysicsDriver> */
    private $physicsDrivers = [];

    /**
     * @return array<int, PhysicsDriver>
     */
    public function getPhysicsDrivers(): array
    {
        return $this->physicsDrivers;
    }

    /**
     * @param resource|string $resource
     */
    public function unpack(&$resource): void
    {
        $count = NetworkPacket::unpackUInt32($resource);

        for ($i = 0; $i < $count; ++$i)
        {
            $physicsDriver = new PhysicsDriver();
            $physicsDriver->unpack($resource);

            $this->physicsDrivers[] = $physicsDriver;
        }
    }
}
