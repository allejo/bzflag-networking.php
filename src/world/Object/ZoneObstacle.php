<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\world\Object;

use allejo\bzflag\generic\FrozenObstacleException;
use allejo\bzflag\networking\Packets\NetworkPacket;
use allejo\bzflag\world\WorldDatabase;

/**
 * @since future
 */
class ZoneObstacle extends Obstacle
{
    /** @var FlagType[] */
    private $flags;

    /** @var int[] */
    private $teams;

    /** @var int[] */
    private $safety;

    /**
     * @since future
     */
    public function __construct(WorldDatabase $database)
    {
        parent::__construct($database, null);

        $this->flags = [];
        $this->teams = [];
        $this->safety = [];
    }

    /**
     * @since future
     *
     * @return FlagType[]
     */
    public function getFlags(): array
    {
        return $this->flags;
    }

    /**
     * @since future
     *
     * @param FlagType[] $flags
     *
     * @throws FrozenObstacleException
     */
    public function setFlags(array $flags): ZoneObstacle
    {
        $this->frozenObstacleCheck();
        $this->flags = $flags;

        return $this;
    }

    /**
     * @since future
     *
     * @return int[]
     */
    public function getTeams(): array
    {
        return $this->teams;
    }

    /**
     * @since future
     *
     * @param int[] $teams
     *
     * @throws FrozenObstacleException
     */
    public function setTeams(array $teams): ZoneObstacle
    {
        $this->frozenObstacleCheck();
        $this->teams = $teams;

        return $this;
    }

    /**
     * @since future
     *
     * @return int[]
     */
    public function getSafety(): array
    {
        return $this->safety;
    }

    /**
     * @since future
     *
     * @param int[] $safety
     *
     * @throws FrozenObstacleException
     */
    public function setSafety(array $safety): ZoneObstacle
    {
        $this->frozenObstacleCheck();
        $this->safety = $safety;

        return $this;
    }

    /**
     * @since future
     *
     * @param mixed $resource
     */
    public function unpack(&$resource): void
    {
        $this->pos = NetworkPacket::unpackVector($resource);
        $this->size = NetworkPacket::unpackVector($resource);
        $this->angle = NetworkPacket::unpackFloat($resource);

        $flagCount = NetworkPacket::unpackUInt16($resource);
        $teamCount = NetworkPacket::unpackUInt16($resource);
        $safetyCount = NetworkPacket::unpackUInt16($resource);

        for ($i = 0; $i < $flagCount; ++$i)
        {
            $this->flags[] = NetworkPacket::unpackFlagType($resource);
        }

        for ($i = 0; $i < $teamCount; ++$i)
        {
            $this->teams[] = NetworkPacket::unpackUInt16($resource);
        }

        for ($i = 0; $i < $safetyCount; ++$i)
        {
            $this->safety[] = NetworkPacket::unpackUInt16($resource);
        }

        $this->freeze();
    }
}
