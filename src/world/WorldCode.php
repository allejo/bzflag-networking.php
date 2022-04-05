<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\world;

/**
 * @see   https://github.com/BZFlag-Dev/bzflag/blob/2.4/include/Protocol.h
 * @since future
 */
abstract class WorldCode
{
    public const HEADER = 0x6865;          // 'he'
    public const BASE = 0x6261;            // 'ba'
    public const BOX = 0x6278;             // 'bx'
    public const END = 0x6564;             // 'ed'
    public const LINK = 0x6C6E;            // 'ln'
    public const PYRAMID = 0x7079;         // 'py'
    public const MESH = 0x6D65;            // 'me'
    public const ARC = 0x6172;             // 'ar'
    public const CONE = 0x636E;            // 'cn'
    public const SPHERE = 0x7370;          // 'sp'
    public const TETRA = 0x7468;           // 'th'
    public const TELEPORTER = 0x7465;      // 'te'
    public const WALL = 0x776C;            // 'wl'
    public const WEAPON = 0x7765;          // 'we'
    public const ZONE = 0x7A6E;            // 'zn'
    public const GROUP = 0x6772;           // 'gr'
    public const GROUP_DEF_START = 0x6473; // 'ds'
    public const GROUP_DEF_END = 0x6465;   // 'de'

    public const SETTINGS_SIZE = 30;
    public const HEADER_SIZE = 10;
    public const BASE_SIZE = 31;
    public const WALL_SIZE = 24;
    public const BOX_SIZE = 29;
    public const END_SIZE = 0;
    public const PYRAMID_SIZE = 29;
    public const MESH_SIZE = 0xA5;  // dummy value, sizes are variable
    public const ARC_SIZE = 85;
    public const CONE_SIZE = 65;
    public const SPHERE_SIZE = 53;
    public const TETRA_SIZE = 66;
    public const TELEPORTER_SIZE = 34;
    public const LINK_SIZE = 4;
    public const WEAPON_SIZE = 24;  // basic size, not including lists
    public const ZONE_SIZE = 34;    // basic size, not including lists
}
