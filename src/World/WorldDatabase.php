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
    private $dynamicColorManager;

    /** @var TextureMatrixManager */
    private $textureMatrixManager;

    /** @var MaterialManager */
    private $materialManager;

    /** @var PhysicsDriverManager */
    private $physicsDriverManager;

    public function __construct($resource)
    {
        $this->headerSize = NetworkPacket::unpackUInt16($resource);
        $this->worldCode = NetworkPacket::unpackUInt16($resource);
        $this->mapVersion = NetworkPacket::unpackUInt16($resource);
        $this->uncompressedSize = NetworkPacket::unpackUInt32($resource);
        $this->databaseSize = NetworkPacket::unpackUInt32($resource);
        $this->database = zlib_decode(fread($resource, $this->databaseSize), $this->uncompressedSize);
        $this->worldCodeEndSize = NetworkPacket::unpackUInt16($resource);
        $this->worldCodeEnd = NetworkPacket::unpackUInt16($resource);

        $this->dynamicColorManager = new DynamicColorManager();
        $this->dynamicColorManager->unpack($this->database);

        $this->textureMatrixManager = new TextureMatrixManager();
        $this->textureMatrixManager->unpack($this->database);

        $this->materialManager = new MaterialManager();
        $this->materialManager->unpack($this->database);

        $this->physicsDriverManager = new PhysicsDriverManager();
        $this->physicsDriverManager->unpack($this->database);
    }
}
