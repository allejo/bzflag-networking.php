<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\World\Object;

use allejo\bzflag\networking\Packets\NetworkPacket;
use allejo\bzflag\networking\World\Modifiers\Material;
use allejo\bzflag\networking\World\Modifiers\MeshTransform;
use allejo\bzflag\networking\World\WorldDatabase;

class SphereObstacle extends Obstacle
{
    const ENUM_EDGE = 1;
    const ENUM_BOTTOM = 1;
    const MATERIAL_COUNT = 1;

    /** @var MeshTransform */
    private $transform;

    /** @var int */
    private $divisions;

    /** @var int */
    private $phyDrv;

    /** @var bool */
    private $smoothBounce;

    /** @var bool */
    private $useNormals;

    /** @var bool */
    private $hemisphere;

    /** @var array{float, float} */
    private $texSize;

    /** @var array<int, Material> */
    private $materials;

    public function __construct()
    {
        $this->texSize = [];
        $this->materials = [];
    }

    public function getTransform(): MeshTransform
    {
        return $this->transform;
    }

    public function getDivisions(): int
    {
        return $this->divisions;
    }

    public function getPhyDrv(): int
    {
        return $this->phyDrv;
    }

    public function isSmoothBounce(): bool
    {
        return $this->smoothBounce;
    }

    public function isUseNormals(): bool
    {
        return $this->useNormals;
    }

    public function isHemisphere(): bool
    {
        return $this->hemisphere;
    }

    /**
     * @return array{float, float}
     */
    public function getTexSize(): array
    {
        return $this->texSize;
    }

    /**
     * @return array<int, Material>
     */
    public function getMaterials(): array
    {
        return $this->materials;
    }

    public function unpack(&$resource): void
    {
        $this->transform = new MeshTransform();
        $this->transform->unpack($resource);

        $this->pos = NetworkPacket::unpackVector($resource);
        $this->size = NetworkPacket::unpackVector($resource);
        $this->angle = NetworkPacket::unpackFloat($resource);
        $this->divisions = (int)NetworkPacket::unpackInt32($resource);
        $this->phyDrv = (int)NetworkPacket::unpackInt32($resource);

        for ($i = 0; $i < 2; ++$i)
        {
            $this->texSize[$i] = NetworkPacket::unpackFloat($resource);
        }

        for ($i = 0; $i < self::MATERIAL_COUNT; ++$i)
        {
            $matIndex = NetworkPacket::unpackInt32($resource);
            $this->materials[] = WorldDatabase::getMaterialManager()->getMaterial($matIndex);
        }

        $stateByte = NetworkPacket::unpackUInt8($resource);
        $this->driveThrough = ($stateByte & (1 << 0)) !== 0;
        $this->shootThrough = ($stateByte & (1 << 1)) !== 0;
        $this->smoothBounce = ($stateByte & (1 << 2)) !== 0;
        $this->useNormals = ($stateByte & (1 << 3)) !== 0;
        $this->hemisphere = ($stateByte & (1 << 4)) !== 0;
        $this->ricochet = ($stateByte & (1 << 5)) !== 0;
    }
}
