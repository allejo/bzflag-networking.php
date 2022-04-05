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
use allejo\bzflag\world\Modifiers\Material;
use allejo\bzflag\world\Modifiers\MeshTransform;
use allejo\bzflag\world\WorldDatabase;

/**
 * @since future
 */
class ConeObstacle extends Obstacle
{
    public const ENUM_EDGE = 0;
    public const ENUM_BOTTOM = 1;
    public const ENUM_START_FACE = 2;
    public const ENUM_END_FACE = 3;
    public const MATERIAL_COUNT = 4;

    /** @var MeshTransform */
    private $transform;

    /** @var float */
    private $sweepAngle;

    /** @var int */
    private $divisions;

    /** @var int */
    private $phyDrv;

    /** @var bool */
    private $smoothBounce;

    /** @var bool */
    private $useNormals;

    /** @var array{float, float} */
    private $texSize = [0.0, 0.0];

    /** @var array<int, Material> */
    private $materials = [];

    /**
     * @since future
     */
    public function __construct(WorldDatabase $database)
    {
        parent::__construct($database, ObstacleType::CONE_TYPE);
    }

    /**
     * @since future
     */
    public function getTransform(): MeshTransform
    {
        return clone $this->transform;
    }

    /**
     * @since future
     *
     * @throws FrozenObstacleException
     *
     * @return $this
     */
    public function setTransform(MeshTransform $transform): self
    {
        $this->frozenObstacleCheck();
        $this->transform = $transform;

        return $this;
    }

    /**
     * @since future
     */
    public function getSweepAngle(): float
    {
        return $this->sweepAngle;
    }

    /**
     * @since future
     *
     * @throws FrozenObstacleException
     *
     * @return $this
     */
    public function setSweepAngle(float $sweepAngle): self
    {
        $this->frozenObstacleCheck();
        $this->sweepAngle = $sweepAngle;

        return $this;
    }

    /**
     * @since future
     */
    public function getDivisions(): int
    {
        return $this->divisions;
    }

    /**
     * @since future
     *
     * @throws FrozenObstacleException
     *
     * @return $this
     */
    public function setDivisions(int $divisions): self
    {
        $this->frozenObstacleCheck();
        $this->divisions = $divisions;

        return $this;
    }

    /**
     * @since future
     */
    public function getPhyDrv(): int
    {
        return $this->phyDrv;
    }

    /**
     * @since future
     *
     * @throws FrozenObstacleException
     *
     * @return $this
     */
    public function setPhyDrv(int $phyDrv): self
    {
        $this->frozenObstacleCheck();
        $this->phyDrv = $phyDrv;

        return $this;
    }

    /**
     * @since future
     */
    public function isSmoothBounce(): bool
    {
        return $this->smoothBounce;
    }

    /**
     * @since future
     *
     * @throws FrozenObstacleException
     *
     * @return $this
     */
    public function setSmoothBounce(bool $smoothBounce): self
    {
        $this->frozenObstacleCheck();
        $this->smoothBounce = $smoothBounce;

        return $this;
    }

    /**
     * @since future
     */
    public function isUseNormals(): bool
    {
        return $this->useNormals;
    }

    /**
     * @since future
     *
     * @throws FrozenObstacleException
     *
     * @return $this
     */
    public function setUseNormals(bool $useNormals): self
    {
        $this->frozenObstacleCheck();
        $this->useNormals = $useNormals;

        return $this;
    }

    /**
     * @since future
     *
     * @return array{float, float}
     */
    public function getTexSize(): array
    {
        return $this->texSize;
    }

    /**
     * @since future
     *
     * @param array{float, float} $texSize
     *
     * @throws FrozenObstacleException
     *
     * @return $this
     */
    public function setTexSize(array $texSize): self
    {
        $this->frozenObstacleCheck();
        $this->texSize = $texSize;

        return $this;
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
     * @param array<int, Material> $materials
     *
     * @throws FrozenObstacleException
     *
     * @return $this
     */
    public function setMaterials(array $materials): self
    {
        $this->frozenObstacleCheck();
        $this->materials = $materials;

        return $this;
    }

    /**
     * @since future
     *
     * @param resource|string $resource
     */
    public function unpack(&$resource): void
    {
        $this->transform = new MeshTransform();
        $this->transform->unpack($resource);

        $this->pos = NetworkPacket::unpackVector($resource);
        $this->size = NetworkPacket::unpackVector($resource);
        $this->angle = NetworkPacket::unpackFloat($resource);
        $this->sweepAngle = NetworkPacket::unpackFloat($resource);
        $this->divisions = NetworkPacket::unpackInt32($resource);
        $this->phyDrv = NetworkPacket::unpackInt32($resource);

        for ($i = 0; $i < 2; ++$i)
        {
            $this->texSize[$i] = NetworkPacket::unpackFloat($resource);
        }

        for ($i = 0; $i < self::MATERIAL_COUNT; ++$i)
        {
            $matIndex = NetworkPacket::unpackInt32($resource);
            $this->materials[$i] = $this->worldDatabase->getMaterialManager()->getMaterial($matIndex);
        }

        $stateByte = NetworkPacket::unpackUInt8($resource);
        $this->driveThrough = ($stateByte & (1 << 0)) !== 0;
        $this->shootThrough = ($stateByte & (1 << 1)) !== 0;
        $this->smoothBounce = ($stateByte & (1 << 2)) !== 0;
        $this->useNormals = ($stateByte & (1 << 3)) !== 0;
        $this->ricochet = ($stateByte & (1 << 4)) !== 0;

        $this->freeze();
    }
}
