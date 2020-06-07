<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\world\Object;

use allejo\bzflag\generic\FreezableClass;
use allejo\bzflag\generic\FrozenObstacleException;
use allejo\bzflag\networking\InaccessibleResourceException;
use allejo\bzflag\networking\Packets\NetworkPacket;
use allejo\bzflag\world\Modifiers\Material;
use allejo\bzflag\world\Modifiers\MeshTransform;
use allejo\bzflag\world\WorldDatabase;

class GroupInstance
{
    use FreezableClass;

    /** @var WorldDatabase */
    private $database;

    /** @var string */
    private $groupDef;

    /** @var string */
    private $name;

    /** @var MeshTransform */
    private $transform;

    /** @var bool */
    private $modifyTeam;

    /** @var bool */
    private $modifyColor;

    /** @var bool */
    private $modifyPhysicsDriver;

    /** @var bool */
    private $modifyMaterial;

    /** @var bool */
    private $driveThrough;

    /** @var bool */
    private $shootThrough;

    /** @var bool */
    private $ricochet;

    /** @var int */
    private $team;

    /** @var array{float, float, float, float} */
    private $tint;

    /** @var int */
    private $phyDrv;

    /** @var Material */
    private $material;

    public function __construct(WorldDatabase $database)
    {
        $this->database = $database;
    }

    public function getGroupDef(): string
    {
        return $this->groupDef;
    }

    /**
     * @throws FrozenObstacleException
     *
     * @return $this
     */
    public function setGroupDef(string $groupDef): self
    {
        $this->frozenObstacleCheck();
        $this->groupDef = $groupDef;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @throws FrozenObstacleException
     *
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->frozenObstacleCheck();
        $this->name = $name;

        return $this;
    }

    public function getTransform(): MeshTransform
    {
        return clone $this->transform;
    }

    /**
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

    public function isModifyTeam(): bool
    {
        return $this->modifyTeam;
    }

    /**
     * @throws FrozenObstacleException
     *
     * @return $this
     */
    public function setModifyTeam(bool $modifyTeam): self
    {
        $this->frozenObstacleCheck();
        $this->modifyTeam = $modifyTeam;

        return $this;
    }

    public function isModifyColor(): bool
    {
        return $this->modifyColor;
    }

    /**
     * @throws FrozenObstacleException
     *
     * @return $this
     */
    public function setModifyColor(bool $modifyColor): self
    {
        $this->frozenObstacleCheck();
        $this->modifyColor = $modifyColor;

        return $this;
    }

    public function isModifyPhysicsDriver(): bool
    {
        return $this->modifyPhysicsDriver;
    }

    /**
     * @throws FrozenObstacleException
     *
     * @return $this
     */
    public function setModifyPhysicsDriver(bool $modifyPhysicsDriver): self
    {
        $this->frozenObstacleCheck();
        $this->modifyPhysicsDriver = $modifyPhysicsDriver;

        return $this;
    }

    public function isModifyMaterial(): bool
    {
        return $this->modifyMaterial;
    }

    /**
     * @throws FrozenObstacleException
     *
     * @return $this
     */
    public function setModifyMaterial(bool $modifyMaterial): self
    {
        $this->frozenObstacleCheck();
        $this->modifyMaterial = $modifyMaterial;

        return $this;
    }

    public function isDriveThrough(): bool
    {
        return $this->driveThrough;
    }

    /**
     * @throws FrozenObstacleException
     *
     * @return $this
     */
    public function setDriveThrough(bool $driveThrough): self
    {
        $this->frozenObstacleCheck();
        $this->driveThrough = $driveThrough;

        return $this;
    }

    public function isShootThrough(): bool
    {
        return $this->shootThrough;
    }

    /**
     * @throws FrozenObstacleException
     *
     * @return $this
     */
    public function setShootThrough(bool $shootThrough): self
    {
        $this->frozenObstacleCheck();
        $this->shootThrough = $shootThrough;

        return $this;
    }

    public function isRicochet(): bool
    {
        return $this->ricochet;
    }

    /**
     * @throws FrozenObstacleException
     *
     * @return $this
     */
    public function setRicochet(bool $ricochet): self
    {
        $this->frozenObstacleCheck();
        $this->ricochet = $ricochet;

        return $this;
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
        $this->team = $team;

        return $this;
    }

    /**
     * @return array{float, float, float, float}
     */
    public function getTint(): array
    {
        return $this->tint;
    }

    /**
     * @param array{float, float, float, float} $tint
     *
     * @throws FrozenObstacleException
     *
     * @return $this
     */
    public function setTint(array $tint): self
    {
        $this->frozenObstacleCheck();
        $this->tint = $tint;

        return $this;
    }

    public function getPhyDrv(): int
    {
        return $this->phyDrv;
    }

    /**
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

    public function getMaterial(): Material
    {
        return $this->material;
    }

    /**
     * @throws FrozenObstacleException
     *
     * @return $this
     */
    public function setMaterial(Material $material): self
    {
        $this->frozenObstacleCheck();
        $this->material = $material;

        return $this;
    }

    /**
     * @param resource|string $resource
     *
     * @throws InaccessibleResourceException
     */
    public function unpack(&$resource): void
    {
        $this->groupDef = NetworkPacket::unpackStdString($resource);
        $this->name = NetworkPacket::unpackStdStringRaw($resource);

        // @TODO Implement "hack to extract material map data"
        // src/obstacle/ObstacleMgr.cxx:257

        $this->transform = new MeshTransform();
        $this->transform->unpack($resource);

        $bits = NetworkPacket::unpackUInt8($resource);

        $this->modifyTeam = ($bits & (1 << 0)) !== 0;
        $this->modifyColor = ($bits & (1 << 1)) !== 0;
        $this->modifyPhysicsDriver = ($bits & (1 << 2)) !== 0;
        $this->modifyMaterial = ($bits & (1 << 3)) !== 0;
        $this->driveThrough = ($bits & (1 << 4)) !== 0;
        $this->shootThrough = ($bits & (1 << 5)) !== 0;
        $this->ricochet = ($bits & (1 << 6)) !== 0;

        if ($this->modifyTeam)
        {
            $this->team = NetworkPacket::unpackUInt16($resource);
        }

        if ($this->modifyColor)
        {
            $this->tint = NetworkPacket::unpack4Float($resource);
        }

        if ($this->modifyPhysicsDriver)
        {
            $this->phyDrv = NetworkPacket::unpackInt32($resource);
        }

        if ($this->modifyMaterial)
        {
            $matIndex = NetworkPacket::unpackInt32($resource);
            $this->material = $this->database->getMaterialManager()->getMaterial($matIndex);
        }

        $this->freeze();
    }
}
