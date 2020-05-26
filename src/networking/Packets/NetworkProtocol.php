<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

abstract class NetworkProtocol
{
    const CALLSIGN_LEN = 32;
    const MOTTO_LEN = 128;
    const SERVER_LEN = 8;
    const MESSAGE_LEN = 128;
    const HASH_LEN = 64;
    const WORLD_SETTING_SIZE = 30;
}
