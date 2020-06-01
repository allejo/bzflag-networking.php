<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\world;

use allejo\bzflag\networking\InaccessibleResourceException;
use allejo\bzflag\networking\Packets\NetworkPacket;
use allejo\bzflag\world\Managers\DynamicColorManager;
use allejo\bzflag\world\Managers\GroupDefinitionManager;
use allejo\bzflag\world\Managers\MaterialManager;
use allejo\bzflag\world\Managers\PhysicsDriverManager;
use allejo\bzflag\world\Managers\TextureMatrixManager;
use allejo\bzflag\world\Managers\TransformManager;

class WorldDatabase implements \JsonSerializable
{
    /** @var int */
    private $headerSize;

    /** @var int */
    private $worldCode;

    /** @var int */
    private $mapVersion;

    /** @var int */
    private $uncompressedSize;

    /** @var int */
    private $databaseSize;

    /** @var string */
    private $database;

    /** @var int */
    private $worldCodeEndSize;

    /** @var int */
    private $worldCodeEnd;

    /** @var DynamicColorManager */
    private $dynamicColorManager;

    /** @var TextureMatrixManager */
    private $textureMatrixManager;

    /** @var MaterialManager */
    private $materialManager;

    /** @var PhysicsDriverManager */
    private $physicsDriverManager;

    /** @var TransformManager */
    private $transformManager;

    /** @var GroupDefinitionManager */
    private $obstacleManager;

    /**
     * @param resource $resource
     *
     * @throws InvalidWorldCompression
     * @throws InvalidWorldDatabase
     * @throws InaccessibleResourceException
     */
    public function __construct(&$resource)
    {
        $this->headerSize = NetworkPacket::unpackUInt16($resource);
        $this->worldCode = NetworkPacket::unpackUInt16($resource);
        $this->mapVersion = NetworkPacket::unpackUInt16($resource);
        $this->uncompressedSize = NetworkPacket::unpackUInt32($resource);
        $this->databaseSize = NetworkPacket::unpackUInt32($resource);

        $worldDatabase = fread($resource, $this->databaseSize);
        if ($worldDatabase === false)
        {
            throw new InvalidWorldDatabase('The world database could not be read from this resource.');
        }

        $uncompressedWorld = zlib_decode($worldDatabase, $this->uncompressedSize);
        if ($uncompressedWorld === false)
        {
            throw new InvalidWorldCompression('The compressed world database could not be expanded.');
        }

        $this->database = $uncompressedWorld;

        $this->worldCodeEndSize = NetworkPacket::unpackUInt16($resource);
        $this->worldCodeEnd = NetworkPacket::unpackUInt16($resource);

        $this->dynamicColorManager = new DynamicColorManager($this);
        $this->dynamicColorManager->unpack($this->database);

        $this->textureMatrixManager = new TextureMatrixManager($this);
        $this->textureMatrixManager->unpack($this->database);

        $this->materialManager = new MaterialManager($this);
        $this->materialManager->unpack($this->database);

        $this->physicsDriverManager = new PhysicsDriverManager($this);
        $this->physicsDriverManager->unpack($this->database);

        $this->transformManager = new TransformManager($this);
        $this->transformManager->unpack($this->database);

        $this->obstacleManager = new GroupDefinitionManager($this);
        $this->obstacleManager->unpack($this->database);
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'colors' => $this->dynamicColorManager->getColors(),
            'textures' => $this->textureMatrixManager->getTextures(),
            'materials' => $this->materialManager->getMaterials(),
            'physicsDrivers' => $this->physicsDriverManager->getPhysicsDrivers(),
            'transforms' => $this->transformManager->getMeshTransforms(),
            'obstacles' => $this->obstacleManager->getWorld()->getObstaclesByType(),
            'groups' => $this->obstacleManager->getGroupDefinitions(),
        ];
    }

    public function getHeaderSize(): int
    {
        return $this->headerSize;
    }

    public function getWorldCode(): int
    {
        return $this->worldCode;
    }

    public function getMapVersion(): int
    {
        return $this->mapVersion;
    }

    public function getUncompressedSize(): int
    {
        return $this->uncompressedSize;
    }

    public function getDatabaseSize(): int
    {
        return $this->databaseSize;
    }

    public function getDatabase(): string
    {
        return $this->database;
    }

    public function getWorldCodeEndSize(): int
    {
        return $this->worldCodeEndSize;
    }

    public function getWorldCodeEnd(): int
    {
        return $this->worldCodeEnd;
    }

    public function getDynamicColorManager(): DynamicColorManager
    {
        return $this->dynamicColorManager;
    }

    public function getTextureMatrixManager(): TextureMatrixManager
    {
        return $this->textureMatrixManager;
    }

    public function getMaterialManager(): MaterialManager
    {
        return $this->materialManager;
    }

    public function getPhysicsDriverManager(): PhysicsDriverManager
    {
        return $this->physicsDriverManager;
    }

    public function getTransformManager(): TransformManager
    {
        return $this->transformManager;
    }

    public function getObstacleManager(): GroupDefinitionManager
    {
        return $this->obstacleManager;
    }
}
