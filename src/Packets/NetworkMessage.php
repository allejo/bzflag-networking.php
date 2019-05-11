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
    const Null = 0x0000;
    const Accept = 0x6163;            // 'ac'
    const AdminInfo = 0x6169;         // 'ai'
    const Alive = 0x616c;             // 'al'
    const AddPlayer = 0x6170;         // 'ap'
    const AutoPilot = 0x6175;         // 'au'
    const CaptureFlag = 0x6366;       // 'cf'
    const CustomSound = 0x6373;       // 'cs'
    const CacheURL = 0x6375;          // 'cu'
    const DropFlag = 0x6466;          // 'df'
    const Enter = 0x656e;             // 'en'
    const Exit = 0x6578;              // 'ex'
    const FlagType = 0x6674;          // 'ft'
    const FlagUpdate = 0x6675;        // 'fu'
    const FetchResources = 0x6672;    // 'fr'
    const GrabFlag = 0x6766;          // 'gf'
    const GMUpdate = 0x676d;          // 'gm'
    const GetWorld = 0x6777;          // 'gw'
    const GameSettings = 0x6773;      // 'gs'
    const GameTime = 0x6774;          // 'gt'
    const Handicap = 0x6863;          // 'hc'
    const Killed = 0x6b6c;            // 'kl'
    const LagState = 0x6c73;          // 'ls'
    const Message = 0x6d67;           // 'mg'
    const NearFlag = 0x4e66;          // 'Nf'
    const NewRabbit = 0x6e52;         // 'nR'
    const NegotiateFlags = 0x6e66;    // 'nf'
    const Pause = 0x7061;             // 'pa'
    const PlayerInfo = 0x7062;        // 'pb'
    const PlayerUpdate = 0x7075;      // 'pu'
    const PlayerUpdateSmall = 0x7073; // 'ps'
    const QueryGame = 0x7167;         // 'qg'
    const QueryPlayers = 0x7170;      // 'qp'
    const Reject = 0x726a;            // 'rj'
    const RemovePlayer = 0x7270;      // 'rp'
    const ReplayReset = 0x7272;       // 'rr'
    const ShotBegin = 0x7362;         // 'sb'
    const Score = 0x7363;             // 'sc'
    const ScoreOver = 0x736f;         // 'so'
    const ShotEnd = 0x7365;           // 'se'
    const SuperKill = 0x736b;         // 'sk'
    const SetVar = 0x7376;            // 'sv'
    const TimeUpdate = 0x746f;        // 'to'
    const Teleport = 0x7470;          // 'tp'
    const TransferFlag = 0x7466;      // 'tf'
    const TeamUpdate = 0x7475;        // 'tu'
    const WantWHash = 0x7768;         // 'wh'
    const WantSettings = 0x7773;      // 'ws'
    const PortalAdd = 0x5061;         // 'Pa'
    const PortalRemove = 0x5072;      // 'Pr'
    const PortalUpdate = 0x5075;      // 'Pu'

    public static function codeFromChars(string $code)
    {
        return hexdec(unpack('H*', $code)[1]);
    }

    public static function charsFromCode(int $chars)
    {
        return pack('H*', dechex($chars));
    }

    /*

    Accept = code_from_chars('ac')
    AdminInfo = code_from_chars('ai')
    Alive = code_from_chars('al')
    AddPlayer = code_from_chars('ap')
    AutoPilot = code_from_chars('au')
    CaptureFlag = code_from_chars('cf')
    CustomSound = code_from_chars('cs')
    CacheURL = code_from_chars('cu')
    DropFlag = code_from_chars('df')
    Enter = code_from_chars('en')
    Exit = code_from_chars('ex')
    FlagType = code_from_chars('ft')
    FlagUpdate = code_from_chars('fu')
    FetchResources = code_from_chars('fr')
    GrabFlag = code_from_chars('gf')
    GMUpdate = code_from_chars('gm')
    GetWorld = code_from_chars('gw')
    GameSettings = code_from_chars('gs')
    GameTime = code_from_chars('gt')
    Handicap = code_from_chars('hc')
    Killed = code_from_chars('kl')
    LagState = code_from_chars('ls')
    Message = code_from_chars('mg')
    NearFlag = code_from_chars('Nf')
    NewRabbit = code_from_chars('nR')
    NegotiateFlags = code_from_chars('nf')
    Pause = code_from_chars('pa')
    PlayerInfo = code_from_chars('pb')
    PlayerUpdate = code_from_chars('pu')
    PlayerUpdateSmall = code_from_chars('ps')
    QueryGame = code_from_chars('qg')
    QueryPlayers = code_from_chars('qp')
    Reject = code_from_chars('rj')
    RemovePlayer = code_from_chars('rp')
    ReplayReset = code_from_chars('rr')
    ShotBegin = code_from_chars('sb')
    Score = code_from_chars('sc')
    ScoreOver = code_from_chars('so')
    ShotEnd = code_from_chars('se')
    SuperKill = code_from_chars('sk')
    SetVar = code_from_chars('sv')
    TimeUpdate = code_from_chars('to')
    Teleport = code_from_chars('tp')
    TransferFlag = code_from_chars('tf')
    TeamUpdate = code_from_chars('tu')
    WantWHash = code_from_chars('wh')
    WantSettings = code_from_chars('ws')
    PortalAdd = code_from_chars('Pa')
    PortalRemove = code_from_chars('Pr')
    PortalUpdate = code_from_chars('Pu')

     */

    // print_r(hexdec(unpack('H*', "ac")[1]))

    // ac == 24931
    // ai == 24937
}
