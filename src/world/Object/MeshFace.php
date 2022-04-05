<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\world\Object;

use allejo\bzflag\networking\Packets\NetworkPacket;
use allejo\bzflag\world\Modifiers\Material;
use allejo\bzflag\world\WorldDatabase;

/**
 * @since future
 */
class MeshFace extends Obstacle
{
    /** @var MeshObstacle */
    private $mesh;

    /** @var int */
    private $vertexCount;

    /** @var array<int, array<int, float>> */
    private $vertices;

    /** @var array<int, array<int, float>> */
    private $normals;

    /** @var array<int, array<int, float>> */
    private $texCoords;

    /** @var Material */
    private $bzMaterial;

    /** @var bool */
    private $smoothBounce;

    /** @var bool */
    private $noClusters;

    /** @var int */
    private $phyDrv;

    /** @var array{float, float, float, float} */
    private $plane; // @phpstan-ignore-line (TODO: Can't remember if we still need these variables)

    /** @var array<int, array{float, float, float, float}> */
    private $edgePlanes; // @phpstan-ignore-line (TODO: Can't remember if we still need these variables)

    /** @var array<int, MeshFace> */
    private $edges; // @phpstan-ignore-line (TODO: Can't remember if we still need these variables)

    /**
     * @since future
     */
    public function __construct(MeshObstacle $mesh, WorldDatabase $database)
    {
        parent::__construct($database, null);

        $this->mesh = $mesh;
        $this->vertices = [];
        $this->normals = [];
        $this->texCoords = [];
    }

    /**
     * @since future
     */
    public function getMesh(): MeshObstacle
    {
        return $this->mesh;
    }

    /**
     * @since future
     */
    public function getVertexCount(): int
    {
        return $this->vertexCount;
    }

    /**
     * @since future
     *
     * @return array<int, array<int, float>>
     */
    public function getVertices(): array
    {
        return $this->vertices;
    }

    /**
     * @since future
     *
     * @return array<int, array<int, float>>
     */
    public function getNormals(): array
    {
        return $this->normals;
    }

    /**
     * @since future
     *
     * @return array<int, array<int, float>>
     */
    public function getTexCoords(): array
    {
        return $this->texCoords;
    }

    /**
     * @since future
     */
    public function getMaterial(): Material
    {
        return $this->bzMaterial;
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
     */
    public function isNoClusters(): bool
    {
        return $this->noClusters;
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
     * @param resource|string $resource
     */
    public function unpack(&$resource): void
    {
        $stateByte = NetworkPacket::unpackUInt8($resource);
        $tmpNormals = ($stateByte & (1 << 0)) !== 0;
        $tmpTexcoords = ($stateByte & (1 << 1)) !== 0;
        $this->driveThrough = ($stateByte & (1 << 2)) !== 0;
        $this->shootThrough = ($stateByte & (1 << 3)) !== 0;
        $this->smoothBounce = ($stateByte & (1 << 4)) !== 0;
        $this->noClusters = ($stateByte & (1 << 5)) !== 0;
        $this->ricochet = ($stateByte & (1 << 6)) !== 0;

        $this->vertexCount = NetworkPacket::unpackInt32($resource);
        $this->vertices = [];
        for ($i = 0; $i < $this->vertexCount; ++$i)
        {
            $index = NetworkPacket::unpackInt32($resource);
            $this->vertices[$i] = $this->mesh->getVertices()[$index];
        }

        if ($tmpNormals)
        {
            $this->normals = [];
            for ($i = 0; $i < $this->vertexCount; ++$i)
            {
                $index = NetworkPacket::unpackInt32($resource);
                $this->normals[$i] = $this->mesh->getNormals()[$index];
            }
        }

        if ($tmpTexcoords)
        {
            $this->texCoords = [];
            for ($i = 0; $i < $this->vertexCount; ++$i)
            {
                $index = NetworkPacket::unpackInt32($resource);
                $this->texCoords[$i] = $this->mesh->getTexCoords()[$index];
            }
        }

        $matIndex = NetworkPacket::unpackInt32($resource);
        $this->bzMaterial = $this->worldDatabase->getMaterialManager()->getMaterial($matIndex);

        $this->phyDrv = NetworkPacket::unpackInt32($resource);
    }

    /**
     * @since future
     */
    protected function getJsonEncodeBlacklist(): array
    {
        return array_merge(parent::getJsonEncodeBlacklist(), [
            'mesh',
        ]);
    }
}
