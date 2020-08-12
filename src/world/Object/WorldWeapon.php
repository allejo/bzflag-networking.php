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
use allejo\bzflag\networking\Exceptions\InaccessibleResourceException;
use allejo\bzflag\networking\Packets\NetworkPacket;
use allejo\bzflag\world\WorldDatabase;

/**
 * @since future
 */
class WorldWeapon implements IWorldDatabaseAware
{
    use FreezableClass;
    use JsonSerializePublicGetters;
    use WorldDatabaseAwareTrait;

    /** @var FlagType */
    private $flagType;

    /** @var array{float, float, float} */
    private $position;

    /** @var float */
    private $direction;

    /** @var float */
    private $initDelay;

    /** @var float[] */
    private $delay;

    /**
     * @since future
     */
    public function __construct(WorldDatabase $database)
    {
        $this->worldDatabase = $database;
        $this->flagType = FlagType::NullFlag();
        $this->position = [0.0, 0.0, 0.0];
        $this->direction = 0.0;
        $this->initDelay = 0.0;
        $this->delay = [];
    }

    /**
     * @since future
     */
    public function getFlagType(): FlagType
    {
        return $this->flagType;
    }

    /**
     * @since future
     *
     * @throws FrozenObstacleException
     */
    public function setFlagType(FlagType $flagType): WorldWeapon
    {
        $this->frozenObstacleCheck();
        $this->flagType = $flagType;

        return $this;
    }

    /**
     * @since future
     *
     * @return array{float, float, float}
     */
    public function getPosition(): array
    {
        return $this->position;
    }

    /**
     * @since future
     *
     * @param array{float, float, float} $position
     *
     * @throws FrozenObstacleException
     */
    public function setPosition(array $position): WorldWeapon
    {
        $this->frozenObstacleCheck();
        $this->position = $position;

        return $this;
    }

    /**
     * @since future
     */
    public function getDirection(): float
    {
        return $this->direction;
    }

    /**
     * @since future
     *
     * @throws FrozenObstacleException
     */
    public function setDirection(float $direction): WorldWeapon
    {
        $this->frozenObstacleCheck();
        $this->direction = $direction;

        return $this;
    }

    /**
     * @since future
     */
    public function getInitDelay(): float
    {
        return $this->initDelay;
    }

    /**
     * @since future
     *
     * @throws FrozenObstacleException
     */
    public function setInitDelay(float $initDelay): WorldWeapon
    {
        $this->frozenObstacleCheck();
        $this->initDelay = $initDelay;

        return $this;
    }

    /**
     * @since future
     *
     * @return float[]
     */
    public function getDelay(): array
    {
        return $this->delay;
    }

    /**
     * @since future
     *
     * @param float[] $delay
     *
     * @throws FrozenObstacleException
     */
    public function setDelay(array $delay): WorldWeapon
    {
        $this->frozenObstacleCheck();
        $this->delay = $delay;

        return $this;
    }

    /**
     * @since future
     *
     * @param resource|string $resource
     *
     * @throws InaccessibleResourceException
     * @throws \InvalidArgumentException
     */
    public function unpack(&$resource): void
    {
        $this->flagType = NetworkPacket::unpackFlagType($resource);
        $this->position = NetworkPacket::unpackVector($resource);
        $this->direction = NetworkPacket::unpackFloat($resource);
        $this->initDelay = NetworkPacket::unpackFloat($resource);

        $delayCount = NetworkPacket::unpackUInt16($resource);

        for ($i = 0; $i < $delayCount; ++$i)
        {
            $this->delay[] = NetworkPacket::unpackFloat($resource);
        }

        $this->freeze();
    }
}
