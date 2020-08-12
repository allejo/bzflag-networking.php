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
class TetraBuilding extends Obstacle
{
    /** @var MeshTransform */
    private $transform;

    /** @var float[][] */
    private $vertices = [];

    /** @var float[][][] */
    private $normals = [];

    /** @var float[][][] */
    private $texCoords = [];

    /** @var bool[] */
    private $useNormals = [];

    /** @var bool[] */
    private $useTexCoords = [];

    /** @var Material[] */
    private $materials = [];

    /**
     * @since future
     */
    public function __construct(WorldDatabase $database)
    {
        parent::__construct($database, ObstacleType::TETRA_TYPE);
    }

    /**
     * @since future
     */
    public function getTransform(): MeshTransform
    {
        return $this->transform;
    }

    /**
     * @since future
     *
     * @throws FrozenObstacleException
     *
     * @return TetraBuilding
     */
    public function setTransform(MeshTransform $transform): self
    {
        $this->frozenObstacleCheck();
        $this->transform = $transform;

        return $this;
    }

    /**
     * @since future
     *
     * @return float[][]
     */
    public function getVertices(): array
    {
        return $this->vertices;
    }

    /**
     * @since future
     *
     * @param float[][] $vertices
     *
     * @throws FrozenObstacleException
     *
     * @return TetraBuilding
     */
    public function setVertices(array $vertices): self
    {
        $this->frozenObstacleCheck();
        $this->vertices = $vertices;

        return $this;
    }

    /**
     * @since future
     *
     * @return float[][][]
     */
    public function getNormals(): array
    {
        return $this->normals;
    }

    /**
     * @since future
     *
     * @param float[][][] $normals
     *
     * @throws FrozenObstacleException
     *
     * @return TetraBuilding
     */
    public function setNormals(array $normals): self
    {
        $this->frozenObstacleCheck();
        $this->normals = $normals;

        return $this;
    }

    /**
     * @since future
     *
     * @return float[][][]
     */
    public function getTexCoords(): array
    {
        return $this->texCoords;
    }

    /**
     * @since future
     *
     * @param float[][][] $texCoords
     *
     * @throws FrozenObstacleException
     *
     * @return TetraBuilding
     */
    public function setTexCoords(array $texCoords): self
    {
        $this->frozenObstacleCheck();
        $this->texCoords = $texCoords;

        return $this;
    }

    /**
     * @since future
     *
     * @return bool[]
     */
    public function getUseNormals(): array
    {
        return $this->useNormals;
    }

    /**
     * @since future
     *
     * @param bool[] $useNormals
     *
     * @throws FrozenObstacleException
     *
     * @return TetraBuilding
     */
    public function setUseNormals(array $useNormals): self
    {
        $this->frozenObstacleCheck();
        $this->useNormals = $useNormals;

        return $this;
    }

    /**
     * @since future
     *
     * @return bool[]
     */
    public function getUseTexCoords(): array
    {
        return $this->useTexCoords;
    }

    /**
     * @since future
     *
     * @param bool[] $useTexCoords
     *
     * @throws FrozenObstacleException
     *
     * @return TetraBuilding
     */
    public function setUseTexCoords(array $useTexCoords): self
    {
        $this->frozenObstacleCheck();
        $this->useTexCoords = $useTexCoords;

        return $this;
    }

    /**
     * @since future
     *
     * @return Material[]
     */
    public function getMaterials(): array
    {
        return $this->materials;
    }

    /**
     * @since future
     *
     * @param Material[] $materials
     *
     * @throws FrozenObstacleException
     *
     * @return TetraBuilding
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
     * @param mixed $resource
     */
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
                for ($i = 0; $i < 3; ++$i)
                {
                    $this->normals[$v][$i] = NetworkPacket::unpackVector($resource);
                }
            }
        }

        // Unpack the texcoords
        $useTexCoordsByte = NetworkPacket::unpackUInt8($resource);
        $this->useTexCoords = self::unpack4Bools($useTexCoordsByte);
        for ($v = 0; $v < 4; ++$v)
        {
            if ($this->useTexCoords[$v])
            {
                for ($i = 0; $i < 3; ++$i)
                {
                    $this->texCoords[$v][$i] = [
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
            $this->materials[$i] = $this->worldDatabase->getMaterialManager()->getMaterial($matIndex);
        }

        $this->freeze();
    }

    /**
     * @since future
     *
     * @return array{bool, bool, bool, bool}
     */
    private static function unpack4Bools(int $byte): array
    {
        return [
            (bool)($byte & (1 << 0)),
            (bool)($byte & (1 << 1)),
            (bool)($byte & (1 << 2)),
            (bool)($byte & (1 << 3)),
        ];
    }
}
