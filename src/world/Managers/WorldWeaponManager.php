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

/**
 * @since future
 */
class WorldWeaponManager extends BaseManager
{
    /** @var WorldWeapon[] */
    private $worldWeapons;

    /**
     * @since future
     */
    public function __construct(WorldDatabase $worldDatabase)
    {
        parent::__construct($worldDatabase);

        $this->worldWeapons = [];
    }

    /**
     * @since future
     *
     * @return WorldWeapon[]
     */
    public function getWorldWeapons(): array
    {
        return $this->worldWeapons;
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
        $count = NetworkPacket::unpackInt32($resource);

        for ($i = 0; $i < $count; ++$i)
        {
            $weapon = new WorldWeapon($this->worldDatabase);
            $weapon->unpack($resource);

            $this->worldWeapons[] = $weapon;
        }
    }
}
