<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\replays;

use allejo\bzflag\generic\JsonSerializePublicGetters;
use allejo\bzflag\networking\Exceptions\InaccessibleResourceException;
use allejo\bzflag\networking\Packets\NetworkPacket;
use allejo\bzflag\networking\Packets\NetworkProtocol;
use allejo\bzflag\networking\Packets\Unpackable;
use allejo\bzflag\world\Exceptions\InvalidWorldCompressionException;
use allejo\bzflag\world\Exceptions\InvalidWorldDatabaseException;
use allejo\bzflag\world\WorldDatabase;

/**
 * @since future
 */
class ReplayHeader implements Unpackable, \JsonSerializable
{
    use JsonSerializePublicGetters;

    /** @var int */
    private $magicNumber = -1;

    /** @var int */
    private $version = -1;

    /** @var int */
    private $offset = 0;

    /** @var int */
    private $fileTime = 0;

    /** @var int */
    private $player = -1;

    /** @var int */
    private $flagsSize = 0;

    /** @var int */
    private $worldSize = 0;

    /** @var string */
    private $callsign = '';

    /** @var string */
    private $motto = '';

    /** @var string */
    private $serverVersion = '';

    /** @var string */
    private $appVersion = '';

    /** @var string */
    private $realHash = '';

    /** @var null|ReplayDuration */
    private $length;

    /** @var WorldDatabase */
    private $worldDatabase;

    /**
     * @since future
     *
     * @param resource $resource
     *
     * @throws InvalidWorldCompressionException
     * @throws InvalidWorldDatabaseException
     * @throws InaccessibleResourceException
     */
    public function __construct($resource)
    {
        $this->magicNumber = NetworkPacket::unpackUInt32($resource);
        $this->version = NetworkPacket::unpackUInt32($resource);
        $this->offset = NetworkPacket::unpackUInt32($resource);
        $this->fileTime = NetworkPacket::unpackInt64($resource);
        $this->player = NetworkPacket::unpackUInt32($resource);
        $this->flagsSize = NetworkPacket::unpackUInt32($resource);
        $this->worldSize = NetworkPacket::unpackUInt32($resource);
        $this->callsign = NetworkPacket::unpackString($resource, NetworkProtocol::CALLSIGN_LEN);
        $this->motto = NetworkPacket::unpackString($resource, NetworkProtocol::MOTTO_LEN);
        $this->serverVersion = NetworkPacket::unpackString($resource, NetworkProtocol::SERVER_LEN);
        $this->appVersion = NetworkPacket::unpackString($resource, NetworkProtocol::MESSAGE_LEN);
        $this->realHash = NetworkPacket::unpackString($resource, NetworkProtocol::HASH_LEN);

        $this->length = new ReplayDuration($this->fileTime);

        // Skip the appropriate number of bytes since we're not making use of this
        // data yet

        fread($resource, 4 + NetworkProtocol::WORLD_SETTING_SIZE);

        if ($this->flagsSize > 0)
        {
            fread($resource, $this->flagsSize);
        }

        $this->worldDatabase = new WorldDatabase($resource);
    }

    /**
     * @since future
     */
    public function getMagicNumber(): int
    {
        return $this->magicNumber;
    }

    /**
     * @since future
     */
    public function getVersion(): int
    {
        return $this->version;
    }

    /**
     * @since future
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @since future
     *
     * @return int The duration of the replay in microseconds
     */
    public function getFileTimeAsMicroseconds(): int
    {
        return $this->fileTime;
    }

    /**
     * @since future
     */
    public function getFileTimeAsSeconds(): int
    {
        return (int)round($this->fileTime / 1000000);
    }

    /**
     * @since future
     */
    public function getFileTimeAsMinutes(): int
    {
        return (int)round($this->getFileTimeAsSeconds() / 60);
    }

    /**
     * @since future
     */
    public function getPlayer(): int
    {
        return $this->player;
    }

    /**
     * @since future
     */
    public function getFlagsSize(): int
    {
        return $this->flagsSize;
    }

    /**
     * @since future
     */
    public function getWorldSize(): int
    {
        return $this->worldSize;
    }

    /**
     * @since future
     */
    public function getWorldDatabase(): WorldDatabase
    {
        return $this->worldDatabase;
    }

    /**
     * @since future
     */
    public function getCallsign(): string
    {
        return $this->callsign;
    }

    /**
     * @since future
     */
    public function getMotto(): string
    {
        return $this->motto;
    }

    /**
     * @since future
     */
    public function getServerVersion(): string
    {
        return $this->serverVersion;
    }

    /**
     * @since future
     */
    public function getAppVersion(): string
    {
        return $this->appVersion;
    }

    /**
     * @since future
     */
    public function getRealHash(): string
    {
        return $this->realHash;
    }

    /**
     * @since future
     */
    public function getLength(): ?ReplayDuration
    {
        return $this->length;
    }
}
