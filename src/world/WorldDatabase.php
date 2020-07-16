<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\world;

use allejo\bzflag\networking\Exceptions\InaccessibleResourceException;
use allejo\bzflag\networking\Packets\NetworkPacket;
use allejo\bzflag\world\Exceptions\InvalidWorldCompressionException;
use allejo\bzflag\world\Exceptions\InvalidWorldDatabaseException;
use allejo\bzflag\world\Managers\BZDBManager;
use allejo\bzflag\world\Managers\DynamicColorManager;
use allejo\bzflag\world\Managers\GroupDefinitionManager;
use allejo\bzflag\world\Managers\LinkManager;
use allejo\bzflag\world\Managers\MaterialManager;
use allejo\bzflag\world\Managers\PhysicsDriverManager;
use allejo\bzflag\world\Managers\TextureMatrixManager;
use allejo\bzflag\world\Managers\TransformManager;
use allejo\bzflag\world\Managers\WorldWeaponManager;
use allejo\bzflag\world\Managers\ZoneManager;
use allejo\bzflag\world\Modifiers\Material;

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

    /** @var string */
    private $worldHash;

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

    /** @var LinkManager */
    private $linkManager;

    /** @var BZDBManager */
    private $bzdbManager;

    /** @var WorldWeaponManager */
    private $worldWeaponManager;

    /** @var ZoneManager */
    private $zoneManager;

    /** @var float */
    private $waterLevel;

    /** @var Material */
    private $waterMaterial;

    /**
     * @param resource $resource
     *
     * @throws InvalidWorldCompressionException
     * @throws InvalidWorldDatabaseException
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
            throw new InvalidWorldDatabaseException('The world database could not be read from this resource.');
        }

        $uncompressedWorld = zlib_decode($worldDatabase, $this->uncompressedSize);
        if ($uncompressedWorld === false)
        {
            throw new InvalidWorldCompressionException('The compressed world database could not be expanded.');
        }

        $this->database = $uncompressedWorld;
        $this->worldHash = sha1($uncompressedWorld);

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

        $this->linkManager = new LinkManager($this);
        $this->linkManager->unpack($this->database);

        $this->bzdbManager = new BZDBManager($this);

        $this->unpackWaterLevel();

        $this->worldWeaponManager = new WorldWeaponManager($this);
        $this->worldWeaponManager->unpack($this->database);

        $this->zoneManager = new ZoneManager($this);
        $this->zoneManager->unpack($this->database);
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'hash' => $this->getWorldHash(),
            'colors' => $this->dynamicColorManager->getColors(),
            'textures' => $this->textureMatrixManager->getTextures(),
            'materials' => $this->materialManager->getMaterials(),
            'physicsDrivers' => $this->physicsDriverManager->getPhysicsDrivers(),
            'transforms' => $this->transformManager->getMeshTransforms(),
            'obstacles' => $this->obstacleManager->getWorld()->getObstacles(),
            'groups' => $this->obstacleManager->getGroupDefinitions(),
            'links' => $this->linkManager->getLinks(),
            'initialBZDB' => [
                'evaluated' => $this->bzdbManager->getBZDBVariables(true),
                'raw' => $this->bzdbManager->getBZDBVariables(),
            ],
            'worldWeapons' => $this->worldWeaponManager->getWorldWeapons(),
            'zones' => $this->zoneManager->getZones(),
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

    public function getWorldHash(): string
    {
        return $this->worldHash;
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

    public function getLinkManager(): LinkManager
    {
        return $this->linkManager;
    }

    public function getBZDBManager(): BZDBManager
    {
        return $this->bzdbManager;
    }

    public function getWorldWeaponManager(): WorldWeaponManager
    {
        return $this->worldWeaponManager;
    }

    public function getZoneManager(): ZoneManager
    {
        return $this->zoneManager;
    }

    /**
     * @throws \InvalidArgumentException
     * @throws InaccessibleResourceException
     */
    private function unpackWaterLevel(): void
    {
        $this->waterLevel = NetworkPacket::unpackFloat($this->database);

        if ($this->waterLevel >= 0)
        {
            $matIndex = NetworkPacket::unpackInt32($this->database);
            $this->waterMaterial = $this->getMaterialManager()->getMaterial($matIndex);
        }
    }
}
