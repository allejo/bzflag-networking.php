<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

/**
 * @since 1.0.0
 */
abstract class NetworkProtocol
{
    public const CALLSIGN_LEN = 32;
    public const MOTTO_LEN = 128;
    public const SERVER_LEN = 8;
    public const MESSAGE_LEN = 128;
    public const HASH_LEN = 64;
    public const WORLD_SETTING_SIZE = 30;
}
