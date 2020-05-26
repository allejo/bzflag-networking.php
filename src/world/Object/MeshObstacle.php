<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\world\Object;

use allejo\bzflag\networking\Packets\NetworkPacket;
use allejo\bzflag\world\WorldDatabase;

class MeshObstacle extends Obstacle
{
    /** @var string */
    private $name;

    /** @var int */
    private $checkCount;

    /** @var string */
    private $checkTypes;

    /** @var array<int, array{float, float, float}> */
    private $checkPoints;

    /** @var int */
    private $vertexCount;

    /** @var array<int, array{float, float, float}> */
    private $vertices;

    /** @var int */
    private $normalCount;

    /** @var array<int, array{float, float, float}> */
    private $normals;

    /** @var int */
    private $texCoordCount;

    /** @var array<int, array{float, float}> */
    private $texCoords;

    /** @var int */
    private $faceCount;

    /** @var int */
    private $faceSize;

    /** @var array<int, MeshFace> */
    private $faces;

    /** @var bool */
    private $smoothBounce;

    /** @var bool */
    private $noClusters;

    /** @var bool */
    private $inverted;

    /** @var MeshDrawInfo */
    private $drawInfo;

    public function __construct(WorldDatabase &$database)
    {
        parent::__construct($database, ObstacleType::MESH_TYPE);

        $this->checkPoints = [];
        $this->vertices = [];
        $this->normals = [];
        $this->texCoords = [];
        $this->faces = [];
        $this->drawInfo = new MeshDrawInfo();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCheckCount(): int
    {
        return $this->checkCount;
    }

    public function getCheckTypes(): string
    {
        return $this->checkTypes;
    }

    /**
     * @return array<int, array{float, float, float}>
     */
    public function getCheckPoints(): array
    {
        return $this->checkPoints;
    }

    public function getVertexCount(): int
    {
        return $this->vertexCount;
    }

    /**
     * @return array<int, array{float, float, float}>
     */
    public function getVertices(): array
    {
        return $this->vertices;
    }

    public function getNormalCount(): int
    {
        return $this->normalCount;
    }

    /**
     * @return array<int, array{float, float, float}>
     */
    public function getNormals(): array
    {
        return $this->normals;
    }

    public function getTexCoordCount(): int
    {
        return $this->texCoordCount;
    }

    /**
     * @return array<int, array{float, float}>
     */
    public function getTexCoords(): array
    {
        return $this->texCoords;
    }

    public function getFaceCount(): int
    {
        return $this->faceCount;
    }

    public function getFaceSize(): int
    {
        return $this->faceSize;
    }

    /**
     * @return array<int, MeshFace>
     */
    public function getFaces(): array
    {
        return $this->faces;
    }

    public function isSmoothBounce(): bool
    {
        return $this->smoothBounce;
    }

    public function isNoClusters(): bool
    {
        return $this->noClusters;
    }

    public function isInverted(): bool
    {
        return $this->inverted;
    }

    public function getDrawInfo(): MeshDrawInfo
    {
        return $this->drawInfo;
    }

    /**
     * @param resource|string $resource
     */
    public function unpack(&$resource): void
    {
        $this->checkCount = NetworkPacket::unpackInt32($resource);
        for ($i = 0; $i < $this->checkCount; ++$i)
        {
            $this->checkTypes[$i] = NetworkPacket::unpackUInt8($resource);
            $this->checkPoints[$i] = NetworkPacket::unpackVector($resource);
        }

        $this->vertexCount = NetworkPacket::unpackInt32($resource);
        for ($i = 0; $i < $this->vertexCount; ++$i)
        {
            $this->vertices[$i] = NetworkPacket::unpackVector($resource);
        }

        $this->normalCount = NetworkPacket::unpackInt8($resource);
        for ($i = 0; $i < $this->normalCount; ++$i)
        {
            $this->normals[$i] = NetworkPacket::unpackVector($resource);
        }

        $this->texCoordCount = NetworkPacket::unpackInt8($resource);
        $texCoordBuf = fread($resource, 4 /* sizeof(float) */ * 2 * $this->texCoordCount);

        $this->faceSize = NetworkPacket::unpackInt32($resource);
        $this->faces = [];
        $this->faceCount = 0;
        for ($i = 0; $i < $this->faceSize; ++$i)
        {
            $this->faces[$this->faceCount] = new MeshFace($this, $this->worldDatabase);
            $this->faces[$this->faceCount]->unpack($resource);

            if (!$this->faces[$this->faceCount]->isValid())
            {
                unset($this->faces[$this->faceCount]);
            }
            else
            {
                ++$this->faceCount;
            }
        }

        $stateByte = NetworkPacket::unpackUInt8($resource);
        $this->driveThrough = ($stateByte & (1 << 0)) !== 0;
        $this->shootThrough = ($stateByte & (1 << 1)) !== 0;
        $this->smoothBounce = ($stateByte & (1 << 2)) !== 0;
        $this->noClusters = ($stateByte & (1 << 3)) !== 0;
        $drawInfoOwner = ($stateByte & (1 << 4)) !== 0;
        $this->ricochet = ($stateByte & (1 << 5)) !== 0;

        if ($drawInfoOwner && ($this->vertexCount >= 1))
        {
            --$this->vertexCount;
        }

        if ($drawInfoOwner && ($this->texCoordCount >= 2))
        {
            // @TODO Unpack drawinfo
        }
    }
}
