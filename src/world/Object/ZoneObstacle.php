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

class ZoneObstacle extends Obstacle
{
    /** @var FlagType[] */
    private $flags;

    /** @var int[] */
    private $teams;

    /** @var int[] */
    private $safety;

    public function __construct(WorldDatabase $database)
    {
        parent::__construct($database, null);

        $this->flags = [];
        $this->teams = [];
        $this->safety = [];
    }

    public function unpack(&$resource): void
    {
        $this->pos = NetworkPacket::unpackVector($resource);
        $this->size = NetworkPacket::unpackVector($resource);
        $this->angle = NetworkPacket::unpackFloat($resource);

        $flagCount = NetworkPacket::unpackUInt16($resource);
        $teamCount = NetworkPacket::unpackUInt16($resource);
        $safetyCount = NetworkPacket::unpackUInt16($resource);

        for ($i = 0; $i < $flagCount; ++$i)
        {
            $this->flags[] = NetworkPacket::unpackFlagType($resource);
        }

        for ($i = 0; $i < $teamCount; ++$i)
        {
            $this->teams[] = NetworkPacket::unpackUInt16($resource);
        }

        for ($i = 0; $i < $safetyCount; ++$i)
        {
            $this->safety[] = NetworkPacket::unpackUInt16($resource);
        }
    }
}
