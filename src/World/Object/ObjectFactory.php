<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\World\Object;

abstract class ObjectFactory
{
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

    public static function new(int $type): Obstacle
    {
        if (!isset(self::$mapping[$type]))
        {
            throw new \InvalidArgumentException("Unknown object type with type ID {$type}.");
        }

        return new self::$mapping[$type]();
    }
}
