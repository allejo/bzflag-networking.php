<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\world\Object;

use allejo\bzflag\generic\FreezableClass;
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
