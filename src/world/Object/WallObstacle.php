<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\world\Object;

use allejo\bzflag\networking\Packets\NetworkPacket;

class WallObstacle extends Obstacle
{
    public function unpack(&$resource): void
    {
        $this->pos = NetworkPacket::unpackVector($resource);
        $this->angle = NetworkPacket::unpackFloat($resource);
        $this->size = [
            -1.0,
            NetworkPacket::unpackFloat($resource),
            NetworkPacket::unpackFloat($resource),
        ];

        $stateByte = NetworkPacket::unpackUInt8($resource);
        $this->ricochet = ($stateByte & (1 << 3)) !== 0;
    }
}
