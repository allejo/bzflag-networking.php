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
use allejo\bzflag\world\Modifiers\Material;

/**
 * @since future
 */
class MaterialManager extends BaseManager
{
    /** @var array<int, Material> */
    private $materials = [];

    /**
     * @since future
     */
    public function getMaterial(int $index): Material
    {
        return $this->materials[$index];
    }

    /**
     * @since future
     *
     * @return array<int, Material>
     */
    public function getMaterials(): array
    {
        return $this->materials;
    }

    /**
     * @since future
     *
     * @param resource|string $resource
     *
     * @throws InaccessibleResourceException
     */
    public function unpack(&$resource): void
    {
        $count = NetworkPacket::unpackUInt32($resource);

        for ($i = 0; $i < $count; ++$i)
        {
            $material = new Material();
            $material->unpack($resource);

            $this->materials[] = $material;
        }
    }
}
