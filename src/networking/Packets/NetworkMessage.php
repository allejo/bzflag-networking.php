<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

use allejo\bzflag\networking\Exceptions\UnableToUnpackNetworkCodeException;

/**
 * @see   https://github.com/BZFlag-Dev/bzflag/blob/2.4/include/Protocol.h
 * @since 1.0.0
 */
abstract class NetworkMessage
{
    public const NULL = 0x0000;
    public const ACCEPT = 0x6163;             // 'ac'
    public const ADMIN_INFO = 0x6169;         // 'ai'
    public const ALIVE = 0x616C;              // 'al'
    public const ADD_PLAYER = 0x6170;         // 'ap'
    public const AUTO_PILOT = 0x6175;         // 'au'
    public const CAPTURE_FLAG = 0x6366;       // 'cf'
    public const CUSTOM_SOUND = 0x6373;       // 'cs'
    public const CACHE_URL = 0x6375;          // 'cu'
    public const DROP_FLAG = 0x6466;          // 'df'
    public const ENTER = 0x656E;              // 'en'
    public const EXIT = 0x6578;               // 'ex'
    public const FLAG_TYPE = 0x6674;          // 'ft'
    public const FLAG_UPDATE = 0x6675;        // 'fu'
    public const FETCH_RESOURCES = 0x6672;    // 'fr'
    public const GRAB_FLAG = 0x6766;          // 'gf'
    public const GM_UPDATE = 0x676D;          // 'gm'
    public const GET_WORLD = 0x6777;          // 'gw'
    public const GAME_SETTINGS = 0x6773;      // 'gs'
    public const GAME_TIME = 0x6774;          // 'gt'
    public const HANDICAP = 0x6863;           // 'hc'
    public const KILLED = 0x6B6C;             // 'kl'
    public const LAG_STATE = 0x6C73;          // 'ls'
    public const MESSAGE = 0x6D67;            // 'mg'
    public const NEAR_FLAG = 0x4E66;          // 'Nf'
    public const NEW_RABBIT = 0x6E52;         // 'nR'
    public const NEGOTIATE_FLAGS = 0x6E66;    // 'nf'
    public const PAUSE = 0x7061;              // 'pa'
    public const PLAYER_INFO = 0x7062;        // 'pb'
    public const PLAYER_UPDATE = 0x7075;      // 'pu'
    public const PLAYER_UPDATE_SMALL = 0x7073; // 'ps'
    public const QUERY_GAME = 0x7167;         // 'qg'
    public const QUERY_PLAYERS = 0x7170;      // 'qp'
    public const REJECT = 0x726A;             // 'rj'
    public const REMOVE_PLAYER = 0x7270;      // 'rp'
    public const REPLAY_RESET = 0x7272;       // 'rr'
    public const SHOT_BEGIN = 0x7362;         // 'sb'
    public const SCORE = 0x7363;              // 'sc'
    public const SCORE_OVER = 0x736F;         // 'so'
    public const SHOT_END = 0x7365;           // 'se'
    public const SUPER_KILL = 0x736B;         // 'sk'
    public const SET_VAR = 0x7376;            // 'sv'
    public const TIME_UPDATE = 0x746F;        // 'to'
    public const TELEPORT = 0x7470;           // 'tp'
    public const TRANSFER_FLAG = 0x7466;      // 'tf'
    public const TEAM_UPDATE = 0x7475;        // 'tu'
    public const WANT_WHASH = 0x7768;         // 'wh'
    public const WANT_SETTINGS = 0x7773;      // 'ws'
    public const PORTAL_ALL = 0x5061;         // 'Pa'
    public const PORTAL_REMOVE = 0x5072;      // 'Pr'
    public const PORTAL_UPDATE = 0x5075;      // 'Pu'

    /**
     * @since 1.1.1 Can now throw `UnableToUnpackNetworkCodeException`
     * @since 1.0.0
     *
     * @throws UnableToUnpackNetworkCodeException
     *
     * @return float|int
     */
    public static function codeFromChars(string $code)
    {
        return hexdec((string)NetworkPacket::safeUnpack('H*', $code)[1]);
    }

    /**
     * @since 1.0.0
     *
     * @return false|string
     */
    public static function charsFromCode(int $chars)
    {
        return pack('H*', dechex($chars));
    }
}
