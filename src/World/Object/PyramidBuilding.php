<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\World\Object;

use allejo\bzflag\networking\Packets\NetworkPacket;

class PyramidBuilding extends Obstacle
{
    public function unpack(&$resource): void
    {
        $this->pos = NetworkPacket::unpackVector($resource);
        $this->angle = NetworkPacket::unpackFloat($resource);
        $this->size = NetworkPacket::unpackVector($resource);

        $stateByte = NetworkPacket::unpackUInt8($resource);
        $this->driveThrough = ($stateByte & self::DRIVE_THRU) !== 0;
        $this->shootThrough = ($stateByte & self::SHOOT_THRU) !== 0;
        $this->ricochet = ($stateByte & self::RICOCHET) !== 0;
        $this->zFlip = ($stateByte & self::FLIP_Z) !== 0;
    }
}
