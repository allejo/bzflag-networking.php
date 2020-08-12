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
class MsgNull extends GamePacket
{
    public const PACKET_TYPE = 'MsgNull';

    /**
     * @since 1.0.0
     */
    protected function unpack(): void
    {
    }
}
