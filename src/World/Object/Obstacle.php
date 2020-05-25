<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\World\Object;

abstract class Obstacle
{
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

    /** @var bool */
    protected $zFlip;

    /** @var array<ObstacleType::*, class-string> */
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

    public function getRotation(): float
    {
        return $this->angle;
    }

    public function getWidth(): float
    {
        return $this->size[0];
    }

    public function getBreadth(): float
    {
        return $this->size[1];
    }

    public function getHeight(): float
    {
        return $this->size[2];
    }

    public function getZFlip(): bool
    {
        return $this->zFlip;
    }

    public function isDriveThrough(): bool
    {
        return $this->driveThrough;
    }

    public function isShootThrough(): bool
    {
        return $this->shootThrough;
    }

    public function isPassable(): bool
    {
        return $this->driveThrough && $this->shootThrough;
    }

    public function canRicochet(): bool
    {
        return $this->ricochet;
    }

    public function isValid(): bool
    {
        return true;
    }

    abstract public function unpack(&$resource): void;

    public static function new(int $type): Obstacle
    {
        if (!isset(self::$mapping[$type]))
        {
            throw new \InvalidArgumentException("Unknown object type with type ID {$type}.");
        }

        return new self::$mapping[$type]();
    }
}
