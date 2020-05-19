<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\World\Object;

use allejo\bzflag\networking\Packets\NetworkPacket;
use allejo\bzflag\networking\World\Material;
use allejo\bzflag\networking\World\MeshTransform;
use allejo\bzflag\networking\World\WorldDatabase;

class TetraBuilding extends Obstacle
{
    /** @var MeshTransform */
    private $transform;

    /** @var float[][] */
    private $vertices;

    /** @var float[][][] */
    private $normals;

    /** @var float[][][] */
    private $texcoords;

    /** @var bool[] */
    private $useNormals;

    /** @var bool[] */
    private $useTexcoords;

    /** @var Material[] */
    private $materials;

    public function __construct()
    {
        $this->vertices = [];
        $this->normals = [];
        $this->texcoords = [];
        $this->useNormals = [];
        $this->useTexcoords = [];
        $this->materials = [];
    }

    public function getTransform(): MeshTransform
    {
        return $this->transform;
    }

    /**
     * @return float[][]
     */
    public function getVertices(): array
    {
        return $this->vertices;
    }

    /**
     * @return float[][][]
     */
    public function getNormals(): array
    {
        return $this->normals;
    }

    /**
     * @return float[][][]
     */
    public function getTexcoords(): array
    {
        return $this->texcoords;
    }

    /**
     * @return bool[]
     */
    public function getUseNormals(): array
    {
        return $this->useNormals;
    }

    /**
     * @return bool[]
     */
    public function getUseTexcoords(): array
    {
        return $this->useTexcoords;
    }

    /**
     * @return Material[]
     */
    public function getMaterials(): array
    {
        return $this->materials;
    }

    public function unpack(&$resource): void
    {
        $stateByte = NetworkPacket::unpackUInt8($resource);
        $this->driveThrough = ($stateByte & (1 << 0)) !== 0;
        $this->shootThrough = ($stateByte & (1 << 1)) !== 0;
        $this->ricochet = ($stateByte & (1 << 2)) !== 0;

        $this->transform = new MeshTransform();
        $this->transform->unpack($resource);

        // Unpack the vertices
        for ($v = 0; $v < 4; ++$v)
        {
            $this->vertices[$v] = NetworkPacket::unpackVector($resource);
        }

        // Unpack the normal
        $useNormalsByte = NetworkPacket::unpackUInt8($resource);
        $this->useNormals = self::unpack4Bools($useNormalsByte);
        for ($v = 0; $v < 4; ++$v)
        {
            if ($this->useNormals[$v])
            {
                for ($v = 0; $v < 3; ++$v)
                {
                    $this->normals[$v][$v] = NetworkPacket::unpackVector($resource);
                }
            }
        }

        // Unpack the texcoords
        $useTexcoordsByte = NetworkPacket::unpackUInt8($resource);
        $this->useTexcoords = self::unpack4Bools($useTexcoordsByte);
        for ($v = 0; $v < 4; ++$v)
        {
            if ($this->useTexcoords[$v])
            {
                for ($i = 0; $i < 3; ++$i)
                {
                    $this->texcoords[$v][$i] = [
                        NetworkPacket::unpackFloat($resource),
                        NetworkPacket::unpackFloat($resource),
                    ];
                }
            }
        }

        // Unpack the materials
        for ($i = 0; $i < 4; ++$i)
        {
            $matIndex = NetworkPacket::unpackInt32($resource);
            $this->materials[$i] = WorldDatabase::getMaterialManager()->getMaterial($matIndex);
        }
    }

    private static function unpack4Bools($byte): array
    {
        $bools = [];

        for ($i = 0; $i < 4; ++$i)
        {
            $bools[$i] = (bool)($byte & (1 << $i));
        }

        return $bools;
    }
}
