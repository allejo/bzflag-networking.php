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
use allejo\bzflag\world\Object\WorldWeapon;
use allejo\bzflag\world\WorldDatabase;

class WorldWeaponManager extends BaseManager
{
    /** @var WorldWeapon[] */
    private $worldWeapons;

    public function __construct(WorldDatabase $worldDatabase)
    {
        parent::__construct($worldDatabase);

        $this->worldWeapons = [];
    }

    /**
     * @return WorldWeapon[]
     */
    public function getWorldWeapons(): array
    {
        return $this->worldWeapons;
    }

    /**
     * @param resource|string $resource
     *
     * @throws \InvalidArgumentException
     * @throws InaccessibleResourceException
     */
    public function unpack(&$resource): void
    {
        $count = NetworkPacket::unpackInt32($resource);

        for ($i = 0; $i < $count; ++$i)
        {
            $weapon = new WorldWeapon($this->worldDatabase);
            $weapon->unpack($resource);

            $this->worldWeapons[] = $weapon;
        }
    }
}
