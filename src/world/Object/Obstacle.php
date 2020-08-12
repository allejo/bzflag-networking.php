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

/**
 * @since future
 */
abstract class Obstacle implements \JsonSerializable, WorldDatabaseAwareInterface
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

    /**
     * @since future
     */
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

    /**
     * @since future
     */
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
     * @since future
     *
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
     * @since future
     *
     * @return null|ObstacleType::*
     */
    public function getObjectType(): ?int
    {
        return $this->objectType;
    }

    /**
     * @since future
     *
     * @return array{float, float, float}
     */
    public function getPosition(): array
    {
        return $this->pos;
    }

    /**
     * @since future
     *
     * @return array{float, float, float}
     */
    public function getSize(): array
    {
        return $this->size;
    }

    /**
     * @since future
     *
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
     *
     * @since future
     */
    public function getAngle(): float
    {
        return $this->angle;
    }

    /**
     * @since future
     *
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
     *
     * @since future
     */
    public function getRotation(): float
    {
        return rad2deg($this->angle);
    }

    /**
     * @since future
     *
     * @throws FrozenObstacleException
     *
     * @return $this
     */
    public function setRotation(float $rotation)
    {
        $this->setAngle(deg2rad($rotation));

        return $this;
    }

    /**
     * @since future
     */
    public function getWidth(): float
    {
        return $this->size[0];
    }

    /**
     * @since future
     *
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

    /**
     * @since future
     */
    public function getBreadth(): float
    {
        return $this->size[1];
    }

    /**
     * @since future
     *
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

    /**
     * @since future
     */
    public function getHeight(): float
    {
        return $this->size[2];
    }

    /**
     * @since future
     *
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

    /**
     * @since future
     */
    public function isDriveThrough(): bool
    {
        return $this->driveThrough;
    }

    /**
     * @since future
     *
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

    /**
     * @since future
     */
    public function isShootThrough(): bool
    {
        return $this->shootThrough;
    }

    /**
     * @since future
     *
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

    /**
     * @since future
     */
    public function isPassable(): bool
    {
        return $this->driveThrough && $this->shootThrough;
    }

    /**
     * @since future
     *
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

    /**
     * @since future
     */
    public function canRicochet(): bool
    {
        return $this->ricochet;
    }

    /**
     * @since future
     *
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

    /**
     * @since future
     */
    public function isValid(): bool
    {
        return true;
    }

    /**
     * @since future
     *
     * @param resource|string $resource
     *
     * @throws InaccessibleResourceException
     */
    abstract public function unpack(&$resource): void;

    /**
     * @since future
     *
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
