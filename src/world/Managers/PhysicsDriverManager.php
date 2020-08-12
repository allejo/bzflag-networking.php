<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\world\Managers;

use allejo\bzflag\networking\Exceptions\InaccessibleResourceException;
use allejo\bzflag\networking\Packets\NetworkPacket;
use allejo\bzflag\world\Modifiers\PhysicsDriver;

/**
 * @since future
 */
class PhysicsDriverManager extends BaseManager
{
    /** @var array<int, PhysicsDriver> */
    private $physicsDrivers = [];

    /**
     * @since future
     *
     * @return array<int, PhysicsDriver>
     */
    public function getPhysicsDrivers(): array
    {
        return $this->physicsDrivers;
    }

    /**
     * @since future
     *
     * @param resource|string $resource
     *
     * @throws InaccessibleResourceException
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
