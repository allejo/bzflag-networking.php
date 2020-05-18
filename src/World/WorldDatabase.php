<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\World;

use allejo\bzflag\networking\Packets\NetworkPacket;

class WorldDatabase
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
    private static $dynamicColorManager;

    /** @var TextureMatrixManager */
    private static $textureMatrixManager;

    /** @var MaterialManager */
    private static $materialManager;

    /** @var PhysicsDriverManager */
    private static $physicsDriverManager;

    /** @var TransformManager */
    private static $transformManager;

    /** @var ObstacleManager */
    private static $obstacleManager;

    public function __construct(&$resource)
    {
        $this->headerSize = NetworkPacket::unpackUInt16($resource);
        $this->worldCode = NetworkPacket::unpackUInt16($resource);
        $this->mapVersion = NetworkPacket::unpackUInt16($resource);
        $this->uncompressedSize = NetworkPacket::unpackUInt32($resource);
        $this->databaseSize = NetworkPacket::unpackUInt32($resource);
        $this->database = zlib_decode(fread($resource, $this->databaseSize), $this->uncompressedSize);
        $this->worldCodeEndSize = NetworkPacket::unpackUInt16($resource);
        $this->worldCodeEnd = NetworkPacket::unpackUInt16($resource);

        self::$dynamicColorManager = new DynamicColorManager();
        self::$dynamicColorManager->unpack($this->database);

        self::$textureMatrixManager = new TextureMatrixManager();
        self::$textureMatrixManager->unpack($this->database);

        self::$materialManager = new MaterialManager();
        self::$materialManager->unpack($this->database);

        self::$physicsDriverManager = new PhysicsDriverManager();
        self::$physicsDriverManager->unpack($this->database);

        self::$transformManager = new TransformManager();
        self::$transformManager->unpack($this->database);

        self::$obstacleManager = new ObstacleManager();
        self::$obstacleManager->unpack($this->database);
    }

    public static function getDynamicColorManager(): DynamicColorManager
    {
        return self::$dynamicColorManager;
    }

    public static function getTextureMatrixManager(): TextureMatrixManager
    {
        return self::$textureMatrixManager;
    }

    public static function getMaterialManager(): MaterialManager
    {
        return self::$materialManager;
    }

    public static function getPhysicsDriverManager(): PhysicsDriverManager
    {
        return self::$physicsDriverManager;
    }

    public static function getTransformManager(): TransformManager
    {
        return self::$transformManager;
    }

    public static function getObstacleManager(): ObstacleManager
    {
        return self::$obstacleManager;
    }
}
