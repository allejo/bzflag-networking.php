<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking;

use allejo\bzflag\networking\Packets\NetworkPacket;
use allejo\bzflag\networking\Packets\NetworkProtocol;
use allejo\bzflag\networking\Packets\Unpackable;
use allejo\bzflag\networking\World\WorldDatabase;

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

    /** @var null|WorldDatabase */
    private $worldDatabase;

    /**
     * @param resource $resource
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

    public function getMagicNumber(): int
    {
        return $this->magicNumber;
    }

    public function getVersion(): int
    {
        return $this->version;
    }

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

    public function getFileTimeAsSeconds(): int
    {
        return (int)round($this->fileTime / 1000000);
    }

    public function getFileTimeAsMinutes(): int
    {
        return (int)round($this->getFileTimeAsSeconds() / 60);
    }

    public function getPlayer(): int
    {
        return $this->player;
    }

    public function getFlagsSize(): int
    {
        return $this->flagsSize;
    }

    public function getWorldSize(): int
    {
        return $this->worldSize;
    }

    public function getCallsign(): string
    {
        return $this->callsign;
    }

    public function getMotto(): string
    {
        return $this->motto;
    }

    public function getServerVersion(): string
    {
        return $this->serverVersion;
    }

    public function getAppVersion(): string
    {
        return $this->appVersion;
    }

    public function getRealHash(): string
    {
        return $this->realHash;
    }

    public function getLength(): ?ReplayDuration
    {
        return $this->length;
    }
}
