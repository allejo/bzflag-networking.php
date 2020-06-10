<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\world\Object;

use allejo\bzflag\generic\FreezableClass;
use allejo\bzflag\generic\FrozenObstacleException;
use allejo\bzflag\generic\JsonSerializePublicGetters;
use allejo\bzflag\networking\InaccessibleResourceException;
use allejo\bzflag\networking\Packets\NetworkPacket;
use allejo\bzflag\world\WorldDatabase;

class GroupDefinition implements \JsonSerializable, IWorldDatabaseAware
{
    use FreezableClass;
    use JsonSerializePublicGetters;

    /** @var WorldDatabase */
    private $worldDatabase;

    /** @var string */
    private $name;

    /** @var bool */
    private $active;

    /** @var array<ObstacleType::*, array<int, Obstacle>> */
    private $lists;

    /** @var array<int, GroupInstance> */
    private $groupInstances;

    public function __construct(string $name, WorldDatabase $database)
    {
        $this->worldDatabase = $database;
        $this->name = $name;
        $this->active = false;
        $this->lists = [
            ObstacleType::WALL_TYPE => [],
            ObstacleType::BOX_TYPE => [],
            ObstacleType::PYR_TYPE => [],
            ObstacleType::BASE_TYPE => [],
            ObstacleType::TELE_TYPE => [],
            ObstacleType::MESH_TYPE => [],
            ObstacleType::ARC_TYPE => [],
            ObstacleType::CONE_TYPE => [],
            ObstacleType::SPHERE_TYPE => [],
            ObstacleType::TETRA_TYPE => [],
        ];
        $this->groupInstances = [];
    }

    public function getWorldDatabase(): WorldDatabase
    {
        return $this->worldDatabase;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @throws FrozenObstacleException
     *
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->frozenObstacleCheck();
        $this->name = $name;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @throws FrozenObstacleException
     *
     * @return $this
     */
    public function setActive(bool $active): self
    {
        $this->frozenObstacleCheck();
        $this->active = $active;

        return $this;
    }

    /**
     * @param null|ObstacleType::* $type
     *
     * @return array<ObstacleType::*, array<int, Obstacle>>|array<int, Obstacle>
     */
    public function getObstaclesByType(?int $type = null): array
    {
        if ($type === null)
        {
            return $this->lists;
        }

        return $this->lists[$type];
    }

    /**
     * @param ObstacleType[]|array<ObstacleType::*, ObstacleType[]> $obstacles
     * @param null|ObstacleType::*                                  $type
     *
     * @throws \InvalidArgumentException
     * @throws FrozenObstacleException
     *
     * @return $this
     */
    public function setObstaclesByType(array $obstacles, ?int $type = null): self
    {
        $this->frozenObstacleCheck();

        if ($type === null)
        {
            if (count($obstacles) !== ObstacleType::OBSTACLE_TYPE_COUNT)
            {
                throw new \InvalidArgumentException('$obstacles does not match the expected structure.');
            }

            for ($i = 0; $i < ObstacleType::OBSTACLE_TYPE_COUNT; ++$i)
            {
                if (!isset($obstacles[$i]))
                {
                    throw new \InvalidArgumentException("No field for Obstacle Type {$i}.");
                }
            }

            $this->lists = $obstacles;
        }
        else
        {
            if (!isset($this->lists[$type]))
            {
                throw new \InvalidArgumentException("Invalid Obstacle Type value: {$type}.");
            }

            $this->lists[$type] = $obstacles;
        }

        return $this;
    }

    /**
     * @return array<int, GroupInstance>
     */
    public function getGroupInstances(): array
    {
        return $this->groupInstances;
    }

    /**
     * @param array<int, GroupInstance> $groupInstances
     *
     * @throws FrozenObstacleException
     *
     * @return $this
     */
    public function setGroupInstances(array $groupInstances): self
    {
        $this->frozenObstacleCheck();
        $this->groupInstances = $groupInstances;

        return $this;
    }

    /**
     * @param resource|string $resource
     *
     * @throws InaccessibleResourceException
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
            $groupInstance = new GroupInstance($this->worldDatabase);
            $groupInstance->unpack($resource);

            $this->groupInstances[] = $groupInstance;
        }

        $this->freeze();
    }

    /**
     * @return array<int, string>
     */
    protected function getJsonEncodeBlacklist(): array
    {
        return ['worldDatabase'];
    }
}
