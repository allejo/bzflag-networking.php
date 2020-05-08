<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\World;

/**
 * @see https://github.com/BZFlag-Dev/bzflag/blob/2.4/include/Protocol.h
 */
abstract class WorldCode
{
    const HEADER = 0x6865;          // 'he'
    const BASE = 0x6261;            // 'ba'
    const BOX = 0x6278;             // 'bx'
    const END = 0x6564;             // 'ed'
    const LINK = 0x6c6e;            // 'ln'
    const PYRAMID = 0x7079;         // 'py'
    const MESH = 0x6D65;            // 'me'
    const ARC = 0x6172;             // 'ar'
    const CONE = 0x636e;            // 'cn'
    const SPHERE = 0x7370;          // 'sp'
    const TETRA = 0x7468;           // 'th'
    const TELEPORTER = 0x7465;      // 'te'
    const WALL = 0x776c;            // 'wl'
    const WEAPON = 0x7765;          // 'we'
    const ZONE = 0x7A6e;            // 'zn'
    const GROUP = 0x6772;           // 'gr'
    const GROUP_DEF_START = 0x6473; // 'ds'
    const GROUP_DEF_END = 0x6465;   // 'de'

    const SETTINGS_SIZE = 30;
    const HEADER_SIZE = 10;
    const BASE_SIZE = 31;
    const WALL_SIZE = 24;
    const BOX_SIZE = 29;
    const END_SIZE = 0;
    const PYRAMID_SIZE = 29;
    const MESH_SIZE = 0xA5;  // dummy value, sizes are variable
    const ARC_SIZE = 85;
    const CONE_SIZE = 65;
    const SPHERE_SIZE = 53;
    const TETRA_SIZE = 66;
    const TELEPORTER_SIZE = 34;
    const LINK_SIZE = 4;
    const WEAPON_SIZE = 24;  // basic size, not including lists
    const ZONE_SIZE = 34;    // basic size, not including lists
}
