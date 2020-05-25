<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\World\Managers;

use allejo\bzflag\networking\Packets\NetworkPacket;
use allejo\bzflag\networking\World\Object\GroupDefinition;

class ObstacleManager
{
    /** @var GroupDefinition */
    private $world;

    /** @var array<int, GroupDefinition> */
    private $groupDefinitions;

    public function getWorld(): GroupDefinition
    {
        return $this->world;
    }

    /**
     * @return array<int, GroupDefinition>
     */
    public function getGroupDefinitions(): array
    {
        return $this->groupDefinitions;
    }

    /**
     * @param resource|string $resource
     */
    public function unpack(&$resource): void
    {
        $this->world = new GroupDefinition('');
        $this->world->unpack($resource);

        $count = NetworkPacket::unpackUInt32($resource);
        for ($i = 0; $i < $count; ++$i)
        {
            $groupDef = new GroupDefinition('');
            $groupDef->unpack($resource);

            $this->groupDefinitions[] = $groupDef;
        }
    }
}
