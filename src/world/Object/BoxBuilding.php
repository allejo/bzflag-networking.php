<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\world\Object;

use allejo\bzflag\networking\Packets\NetworkPacket;
use allejo\bzflag\world\WorldDatabase;

/**
 * @since future
 */
class BoxBuilding extends Obstacle
{
    /**
     * @since future
     */
    public function __construct(WorldDatabase $database)
    {
        parent::__construct($database, ObstacleType::BOX_TYPE);
    }

    /**
     * @since future
     *
     * @param resource|string $resource
     */
    public function unpack(&$resource): void
    {
        $this->pos = NetworkPacket::unpackVector($resource);
        $this->angle = NetworkPacket::unpackFloat($resource);
        $this->size = NetworkPacket::unpackVector($resource);

        $stateByte = NetworkPacket::unpackUInt8($resource);
        $this->driveThrough = ($stateByte & (1 << 0)) !== 0;
        $this->shootThrough = ($stateByte & (1 << 1)) !== 0;
        $this->ricochet = ($stateByte & (1 << 3)) !== 0;

        $this->freeze();
    }
}
