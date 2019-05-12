<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

abstract class NetworkMessage
{
    const NULL = 0x0000;
    const ACCEPT = 0x6163;             // 'ac'
    const ADMIN_INFO = 0x6169;         // 'ai'
    const ALIVE = 0x616c;              // 'al'
    const ADD_PLAYER = 0x6170;         // 'ap'
    const AUTO_PILOT = 0x6175;         // 'au'
    const CAPTURE_FLAG = 0x6366;       // 'cf'
    const CUSTOM_SOUND = 0x6373;       // 'cs'
    const CACHE_URL = 0x6375;          // 'cu'
    const DROP_FLAG = 0x6466;          // 'df'
    const ENTER = 0x656e;              // 'en'
    const EXIT = 0x6578;               // 'ex'
    const FLAG_TYPE = 0x6674;          // 'ft'
    const FLAG_UPDATE = 0x6675;        // 'fu'
    const FETCH_RESOURCES = 0x6672;    // 'fr'
    const GRAB_FLAG = 0x6766;          // 'gf'
    const GM_UPDATE = 0x676d;          // 'gm'
    const GET_WORLD = 0x6777;          // 'gw'
    const GAME_SETTINGS = 0x6773;      // 'gs'
    const GAME_TIME = 0x6774;          // 'gt'
    const HANDICAP = 0x6863;           // 'hc'
    const KILLED = 0x6b6c;             // 'kl'
    const LAG_STATE = 0x6c73;          // 'ls'
    const MESSAGE = 0x6d67;            // 'mg'
    const NEAR_FLAG = 0x4e66;          // 'Nf'
    const NEW_RABBIT = 0x6e52;         // 'nR'
    const NEGOTIATE_FLAGS = 0x6e66;    // 'nf'
    const PAUSE = 0x7061;              // 'pa'
    const PLAYER_INFO = 0x7062;        // 'pb'
    const PLAYER_UPDATE = 0x7075;      // 'pu'
    const PLAYER_UPDATE_SMALL = 0x7073; // 'ps'
    const QUERY_GAME = 0x7167;         // 'qg'
    const QUERY_PLAYERS = 0x7170;      // 'qp'
    const REJECT = 0x726a;             // 'rj'
    const REMOVE_PLAYER = 0x7270;      // 'rp'
    const REPLAY_RESET = 0x7272;       // 'rr'
    const SHOT_BEGIN = 0x7362;         // 'sb'
    const SCORE = 0x7363;              // 'sc'
    const SCORE_OVER = 0x736f;         // 'so'
    const SHOT_END = 0x7365;           // 'se'
    const SUPER_KILL = 0x736b;         // 'sk'
    const SET_VAR = 0x7376;            // 'sv'
    const TIME_UPDATE = 0x746f;        // 'to'
    const TELEPORT = 0x7470;           // 'tp'
    const TRANSFER_FLAG = 0x7466;      // 'tf'
    const TEAM_UPDATE = 0x7475;        // 'tu'
    const WANT_WHASH = 0x7768;         // 'wh'
    const WANT_SETTINGS = 0x7773;      // 'ws'
    const PORTAL_ALL = 0x5061;         // 'Pa'
    const PORTAL_REMOVE = 0x5072;      // 'Pr'
    const PORTAL_UPDATE = 0x5075;      // 'Pu'

    public static function codeFromChars(string $code)
    {
        return hexdec(unpack('H*', $code)[1]);
    }

    public static function charsFromCode(int $chars)
    {
        return pack('H*', dechex($chars));
    }
}
