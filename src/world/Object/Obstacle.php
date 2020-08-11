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
use allejo\bzflag\world\WorldDatabase;

abstract class Obstacle implements \JsonSerializable, IWorldDatabaseAware
{
    use FreezableClass;
    use JsonSerializePublicGetters;
    use WorldDatabaseAwareTrait;

    /** @var null|ObstacleType::* */
    protected $objectType;

    /** @var array{float, float, float} */
    protected $pos;

    /** @var array{float, float, float} */
    protected $size;

    /** @var float */
    protected $angle;

    /** @var bool */
    protected $driveThrough;

    /** @var bool */
    protected $shootThrough;

    /** @var bool */
    protected $ricochet;

    /** @var array<ObstacleType::*, class-string<\allejo\bzflag\world\Object\Obstacle>> */
    private static $mapping = [
        ObstacleType::WALL_TYPE => WallObstacle::class,
        ObstacleType::BOX_TYPE => BoxBuilding::class,
        ObstacleType::PYR_TYPE => PyramidBuilding::class,
        ObstacleType::BASE_TYPE => BaseBuilding::class,
        ObstacleType::TELE_TYPE => Teleporter::class,
        ObstacleType::MESH_TYPE => MeshObstacle::class,
        ObstacleType::ARC_TYPE => ArcObstacle::class,
        ObstacleType::CONE_TYPE => ConeObstacle::class,
        ObstacleType::SPHERE_TYPE => SphereObstacle::class,
        ObstacleType::TETRA_TYPE => TetraBuilding::class,
    ];

    protected function __construct(WorldDatabase $database, ?int $obstacleType)
    {
        $this->worldDatabase = $database;
        $this->objectType = $obstacleType;
        $this->pos = [0, 0, 0];
        $this->size = [0, 0, 0];
        $this->angle = 0;
        $this->driveThrough = false;
        $this->shootThrough = false;
        $this->ricochet = false;
    }

    public static function new(int $type, WorldDatabase $database): Obstacle
    {
        if (!isset(self::$mapping[$type]))
        {
            throw new \InvalidArgumentException("Unknown object type with type ID {$type}.");
        }

        $objClass = self::$mapping[$type];

        return new $objClass($database);
    }

    /**
     * @param array{float, float, float} $pos
     *
     * @throws FrozenObstacleException
     *
     * @return $this
     */
    public function setPosition(array $pos)
    {
        $this->frozenObstacleCheck();
        $this->pos = $pos;

        return $this;
    }

    /**
     * @return null|ObstacleType::*
     */
    public function getObjectType(): ?int
    {
        return $this->objectType;
    }

    /**
     * @return array{float, float, float}
     */
    public function getPosition(): array
    {
        return $this->pos;
    }

    /**
     * @return array{float, float, float}
     */
    public function getSize(): array
    {
        return $this->size;
    }

    /**
     * @param array{float, float, float} $size
     *
     * @throws FrozenObstacleException
     *
     * @return $this
     */
    public function setSize(array $size)
    {
        $this->frozenObstacleCheck();
        $this->size = $size;

        return $this;
    }

    /**
     * Get the angle (in radians) at which this obstacle is rotated.
     */
    public function getAngle(): float
    {
        return $this->angle;
    }

    /**
     * @throws FrozenObstacleException
     *
     * @return $this
     */
    public function setAngle(float $angle)
    {
        $this->frozenObstacleCheck();
        $this->angle = $angle;

        return $this;
    }

    /**
     * Get the angle (in degrees) at which this obstacle is rotated.
     */
    public function getRotation(): float
    {
        return rad2deg($this->angle);
    }

    /**
     * @throws FrozenObstacleException
     *
     * @return $this
     */
    public function setRotation(float $rotation)
    {
        $this->setAngle(deg2rad($rotation));

        return $this;
    }

    public function getWidth(): float
    {
        return $this->size[0];
    }

    /**
     * @throws FrozenObstacleException
     *
     * @return $this
     */
    public function setWidth(float $width)
    {
        $this->frozenObstacleCheck();
        $this->size[0] = $width;

        return $this;
    }

    public function getBreadth(): float
    {
        return $this->size[1];
    }

    /**
     * @throws FrozenObstacleException
     *
     * @return $this
     */
    public function setBreadth(float $breath)
    {
        $this->frozenObstacleCheck();
        $this->size[1] = $breath;

        return $this;
    }

    public function getHeight(): float
    {
        return $this->size[2];
    }

    /**
     * @throws FrozenObstacleException
     *
     * @return $this
     */
    public function setHeight(float $height)
    {
        $this->frozenObstacleCheck();
        $this->size[2] = $height;

        return $this;
    }

    public function isDriveThrough(): bool
    {
        return $this->driveThrough;
    }

    /**
     * @throws FrozenObstacleException
     *
     * @return $this
     */
    public function setDriveThrough(bool $driveThrough)
    {
        $this->frozenObstacleCheck();
        $this->driveThrough = $driveThrough;

        return $this;
    }

    public function isShootThrough(): bool
    {
        return $this->shootThrough;
    }

    /**
     * @throws FrozenObstacleException
     *
     * @return $this
     */
    public function setShootThrough(bool $shootThrough)
    {
        $this->frozenObstacleCheck();
        $this->shootThrough = $shootThrough;

        return $this;
    }

    public function isPassable(): bool
    {
        return $this->driveThrough && $this->shootThrough;
    }

    /**
     * @throws FrozenObstacleException
     *
     * @return $this
     */
    public function setPassable()
    {
        $this->frozenObstacleCheck();
        $this->driveThrough = true;
        $this->shootThrough = true;

        return $this;
    }

    public function canRicochet(): bool
    {
        return $this->ricochet;
    }

    /**
     * @throws FrozenObstacleException
     *
     * @return $this
     */
    public function setRicochet(bool $ricochet)
    {
        $this->frozenObstacleCheck();
        $this->ricochet = $ricochet;

        return $this;
    }

    public function isValid(): bool
    {
        return true;
    }

    /**
     * @param resource|string $resource
     *
     * @throws InaccessibleResourceException
     */
    abstract public function unpack(&$resource): void;

    /**
     * @return array<int, string>
     */
    protected function getJsonEncodeBlacklist(): array
    {
        return [
            'worldDatabase',
            'frozen',
            'valid',
        ];
    }
}
