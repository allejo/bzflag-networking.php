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

    public function __construct(WorldDatabase $database)
    {
        $this->worldDatabase = $database;
        $this->flagType = FlagType::NullFlag();
        $this->position = [0.0, 0.0, 0.0];
        $this->direction = 0.0;
        $this->initDelay = 0.0;
        $this->delay = [];
    }

    public function getFlagType(): FlagType
    {
        return $this->flagType;
    }

    /**
     * @throws FrozenObstacleException
     */
    public function setFlagType(FlagType $flagType): WorldWeapon
    {
        $this->frozenObstacleCheck();

        $this->flagType = $flagType;

        return $this;
    }

    /**
     * @return array{float, float, float}
     */
    public function getPosition(): array
    {
        return $this->position;
    }

    /**
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

    public function getDirection(): float
    {
        return $this->direction;
    }

    /**
     * @throws FrozenObstacleException
     */
    public function setDirection(float $direction): WorldWeapon
    {
        $this->frozenObstacleCheck();

        $this->direction = $direction;

        return $this;
    }

    public function getInitDelay(): float
    {
        return $this->initDelay;
    }

    /**
     * @throws FrozenObstacleException
     */
    public function setInitDelay(float $initDelay): WorldWeapon
    {
        $this->frozenObstacleCheck();

        $this->initDelay = $initDelay;

        return $this;
    }

    /**
     * @return float[]
     */
    public function getDelay(): array
    {
        return $this->delay;
    }

    /**
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
     * @param resource|string $resource
     *
     * @throws \InvalidArgumentException
     * @throws InaccessibleResourceException
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
