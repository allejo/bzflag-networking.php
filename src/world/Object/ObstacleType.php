<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\world\Object;

/**
 * @since future
 */
abstract class ObstacleType
{
    public const WALL_TYPE = 0;
    public const BOX_TYPE = 1;
    public const PYR_TYPE = 2;
    public const BASE_TYPE = 3;
    public const TELE_TYPE = 4;
    public const MESH_TYPE = 5;
    public const ARC_TYPE = 6;
    public const CONE_TYPE = 7;
    public const SPHERE_TYPE = 8;
    public const TETRA_TYPE = 9;
    public const OBSTACLE_TYPE_COUNT = 10;
}
