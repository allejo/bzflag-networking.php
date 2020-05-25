<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\World\Managers;

use allejo\bzflag\networking\Packets\NetworkPacket;
use allejo\bzflag\networking\World\Modifiers\Material;

class MaterialManager
{
    /** @var array<int, Material> */
    private $materials;

    public function __construct()
    {
        $this->materials = [];
    }

    public function getMaterial(int $index): Material
    {
        return $this->materials[$index];
    }

    public function getMaterials(): array
    {
        return $this->materials;
    }

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
