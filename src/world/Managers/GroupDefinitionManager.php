<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\world\Managers;

use allejo\bzflag\networking\InaccessibleResourceException;
use allejo\bzflag\networking\Packets\NetworkPacket;
use allejo\bzflag\world\GroupDefinitionNotFoundException;
use allejo\bzflag\world\Object\GroupDefinition;

class GroupDefinitionManager extends BaseManager
{
    /** @var GroupDefinition */
    private $world;

    /** @var array<string, GroupDefinition> */
    private $groupDefinitions = [];

    public function getWorld(): GroupDefinition
    {
        return $this->world;
    }

    /**
     * @throws GroupDefinitionNotFoundException when there's no group definition with the specified name
     */
    public function getGroupDefinition(string $name): GroupDefinition
    {
        if (isset($this->groupDefinitions[$name]))
        {
            return $this->groupDefinitions[$name];
        }

        throw new GroupDefinitionNotFoundException("No group definition with the name '{$name}' was found");
    }

    /**
     * @return array<string, GroupDefinition>
     */
    public function getGroupDefinitions(): array
    {
        return $this->groupDefinitions;
    }

    /**
     * @param resource|string $resource
     *
     * @throws InaccessibleResourceException
     */
    public function unpack(&$resource): void
    {
        $this->world = new GroupDefinition('', $this->worldDatabase);
        $this->world->unpack($resource);

        $count = NetworkPacket::unpackUInt32($resource);
        for ($i = 0; $i < $count; ++$i)
        {
            $groupDef = new GroupDefinition('', $this->worldDatabase);
            $groupDef->unpack($resource);

            $this->groupDefinitions[$groupDef->getName()] = $groupDef;
        }
    }
}
