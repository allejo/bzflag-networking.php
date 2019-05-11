<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking;

use allejo\bzflag\networking\Packets\IUnpackable;
use allejo\bzflag\networking\Packets\NetworkProtocol;
use allejo\bzflag\networking\Packets\Packet;

/**
 * @internal
 */
class ReplayHeader implements IUnpackable
{
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

    /** @var ReplayDuration|null */
    private $length = null;

    /**
     * @param resource $resource
     */
    public function __construct($resource)
    {
        $this->magicNumber = Packet::unpackUInt32($resource);
        $this->version = Packet::unpackUInt32($resource);
        $this->offset = Packet::unpackUInt32($resource);
        $this->fileTime = Packet::unpackInt64($resource);
        $this->player = Packet::unpackUInt32($resource);
        $this->flagsSize = Packet::unpackUInt32($resource);
        $this->worldSize = Packet::unpackUInt32($resource);
        $this->callsign = Packet::unpackString($resource, NetworkProtocol::CALLSIGN_LEN);
        $this->motto = Packet::unpackString($resource, NetworkProtocol::MOTTO_LEN);
        $this->serverVersion = Packet::unpackString($resource, NetworkProtocol::SERVER_LEN);
        $this->appVersion = Packet::unpackString($resource, NetworkProtocol::MESSAGE_LEN);
        $this->realHash = Packet::unpackString($resource, NetworkProtocol::HASH_LEN);

        $this->length = new ReplayDuration($this->fileTime);

        // Skip the appropriate number of bytes since we're not making use of this
        // data yet

        fread($resource, 4 + NetworkProtocol::WORLD_SETTING_SIZE);

        if ($this->flagsSize > 0)
        {
            fread($resource, $this->flagsSize);
        }

        fread($resource, $this->worldSize);
    }

    /**
     * @return int
     */
    public function getMagicNumber(): int
    {
        return $this->magicNumber;
    }

    /**
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @return int The duration of the replay in microseconds
     */
    public function getFileTimeAsMicroseconds(): int
    {
        return $this->fileTime;
    }

    /**
     * @return int
     */
    public function getFileTimeAsSeconds(): int
    {
        return (int)round($this->fileTime / 1000000);
    }

    /**
     * @param bool $round
     *
     * @return int
     */
    public function getFileTimeAsMinutes(): int
    {
        return (int)round($this->getFileTimeAsSeconds() / 60);
    }

    /**
     * @return int
     */
    public function getPlayer(): int
    {
        return $this->player;
    }

    /**
     * @return int
     */
    public function getFlagsSize(): int
    {
        return $this->flagsSize;
    }

    /**
     * @return int
     */
    public function getWorldSize(): int
    {
        return $this->worldSize;
    }

    /**
     * @return string
     */
    public function getCallsign(): string
    {
        return $this->callsign;
    }

    /**
     * @return string
     */
    public function getMotto(): string
    {
        return $this->motto;
    }

    /**
     * @return string
     */
    public function getServerVersion(): string
    {
        return $this->serverVersion;
    }

    /**
     * @return string
     */
    public function getAppVersion(): string
    {
        return $this->appVersion;
    }

    /**
     * @return string
     */
    public function getRealHash(): string
    {
        return $this->realHash;
    }

    /**
     * @return ReplayDuration|null
     */
    public function getLength(): ?ReplayDuration
    {
        return $this->length;
    }
}
