<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\World\Object;

use allejo\bzflag\networking\Packets\NetworkPacket;
use allejo\bzflag\networking\World\WorldDatabase;

class GroupDefinition
{
    /** @var WorldDatabase */
    private $worldDatabase;

    /** @var string */
    private $name;

    /** @var bool */
    private $active;

    /** @var array<int, array<int, Obstacle>> */
    private $lists;

    /** @var array<int, GroupInstance> */
    private $groups;

    public function __construct(string $name, WorldDatabase &$database)
    {
        $this->name = $name;
        $this->active = false;
        $this->worldDatabase = &$database;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @return array<int, array<int, Obstacle>>
     */
    public function getLists(): array
    {
        return $this->lists;
    }

    /**
     * @return array<int, GroupInstance>
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    /**
     * @param resource|string $resource
     */
    public function unpack(&$resource): void
    {
        $this->name = NetworkPacket::unpackStdString($resource);

        for ($type = 0; $type < ObstacleType::OBSTACLE_TYPE_COUNT; ++$type)
        {
            $count = NetworkPacket::unpackUInt32($resource);

            for ($i = 0; $i < $count; ++$i)
            {
                $obstacle = Obstacle::new($type, $this->worldDatabase);
                $obstacle->unpack($resource);

                if ($obstacle->isValid())
                {
                    $this->lists[$type][] = $obstacle;
                }
            }
        }

        $count = NetworkPacket::unpackUInt32($resource);
        for ($i = 0; $i < $count; ++$i)
        {
            $groupInstance = new GroupInstance();
            $groupInstance->unpack($resource);

            $this->groups[] = $groupInstance;
        }
    }
}
