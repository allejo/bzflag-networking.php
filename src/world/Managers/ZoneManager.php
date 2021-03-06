<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\world\Managers;

use allejo\bzflag\networking\Exceptions\InaccessibleResourceException;
use allejo\bzflag\networking\Packets\NetworkPacket;
use allejo\bzflag\world\Object\ZoneObstacle;
use allejo\bzflag\world\WorldDatabase;

/**
 * @since future
 */
class ZoneManager extends BaseManager
{
    /** @var ZoneObstacle[] */
    private $zones;

    /**
     * @since future
     */
    public function __construct(WorldDatabase $worldDatabase)
    {
        parent::__construct($worldDatabase);

        $this->worldDatabase = $worldDatabase;
        $this->zones = [];
    }

    /**
     * @since future
     *
     * @return ZoneObstacle[]
     */
    public function getZones(): array
    {
        return $this->zones;
    }

    /**
     * @since future
     *
     * @param resource|string $resource
     *
     * @throws InaccessibleResourceException
     * @throws \InvalidArgumentException
     */
    public function unpack(&$resource): void
    {
        $count = NetworkPacket::unpackUInt32($resource);

        for ($i = 0; $i < $count; ++$i)
        {
            $zone = new ZoneObstacle($this->worldDatabase);
            $zone->unpack($resource);

            $this->zones[] = $zone;
        }
    }
}
