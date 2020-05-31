<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\world\Object;

use allejo\bzflag\generic\FrozenObstacleException;
use allejo\bzflag\networking\Packets\NetworkPacket;
use allejo\bzflag\world\WorldDatabase;

class BaseBuilding extends Obstacle
{
    /** @var int */
    private $team;

    public function __construct(WorldDatabase $database)
    {
        parent::__construct($database, ObstacleType::BASE_TYPE);
    }

    public function getTeam(): int
    {
        return $this->team;
    }

    /**
     * @throws FrozenObstacleException
     *
     * @return $this
     */
    public function setTeam(int $team): self
    {
        $this->frozenObstacleCheck();

        if ($team < 1 || $team > 4)
        {
            throw new \InvalidArgumentException('A team value can only range from 1 - 4 (inclusive)');
        }

        $this->team = $team;

        return $this;
    }

    /**
     * @param resource|string $resource
     */
    public function unpack(&$resource): void
    {
        $shortTeam = NetworkPacket::unpackUInt16($resource);
        $this->team = $shortTeam;

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
