<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\World\Object;

abstract class ObstacleType
{
    const WALL_TYPE = 0;
    const BOX_TYPE = 1;
    const PYR_TYPE = 2;
    const BASE_TYPE = 3;
    const TELE_TYPE = 4;
    const MESH_TYPE = 5;
    const ARC_TYPE = 6;
    const CONE_TYPE = 7;
    const SPHERE_TYPE = 8;
    const TETRA_TYPE = 9;
    const OBSTACLE_TYPE_COUNT = 10;
}
