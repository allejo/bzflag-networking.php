<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

use allejo\bzflag\networking\GameData\FiringInfoData;
use allejo\bzflag\networking\GameData\FlagData;
use allejo\bzflag\networking\GameData\PlayerState;
use allejo\bzflag\networking\GameData\ShotData;
use allejo\bzflag\networking\InaccessibleResourceException;
use allejo\bzflag\networking\InvalidTimestampFormatException;

/**
 * A raw network packet that was sent and contains data for game packets.
 *
 * @see https://www.php.net/manual/en/function.pack.php
 */
class NetworkPacket implements Unpackable
{
    const SMALL_SCALE = 32766.0;
    const SMALL_MAX_DIST = 0.02 * NetworkPacket::SMALL_SCALE;
    const SMALL_MAX_VEL = 0.01 * NetworkPacket::SMALL_SCALE;
    const SMALL_MAX_ANG_VEL = 0.001 * NetworkPacket::SMALL_SCALE;

    /** @var int */
    private $mode;

    /** @var int */
    private $code;

    /** @var int */
    private $length;

    /** @var int */
    private $nextFilePos;

    /** @var int */
    private $prevFilePos;

    /** @var \DateTime */
    private $timestamp;

    /** @var false|string */
    private $data;

    /**
     * @param resource $resource
     *
     * @throws InaccessibleResourceException
     * @throws InvalidTimestampFormatException
     * @throws PacketInvalidException
     */
    public function __construct($resource)
    {
        $buffer = fread($resource, 32);

        if ($buffer === false || feof($resource))
        {
            throw new PacketInvalidException('The given resource can no longer be read.');
        }

        $this->mode = self::unpackUInt16($buffer);
        $this->code = self::unpackUInt16($buffer);
        $this->length = self::unpackUInt32($buffer);
        $this->nextFilePos = self::unpackUInt32($buffer);
        $this->prevFilePos = self::unpackUInt32($buffer);
        $this->timestamp = self::unpackTimestamp($buffer);

        if ($this->length > 0)
        {
            $this->data = fread($resource, $this->length);
        }
    }

    public function getMode(): int
    {
        return $this->mode;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function getLength(): int
    {
        return $this->length;
    }

    public function getNextFilePos(): int
    {
        return $this->nextFilePos;
    }

    public function getPrevFilePos(): int
    {
        return $this->prevFilePos;
    }

    public function getTimestamp(): \DateTime
    {
        return clone $this->timestamp;
    }

    /**
     * @return false|string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param resource|string $buffer
     *
     * @throws InaccessibleResourceException
     */
    public static function unpackInt8(&$buffer): int
    {
        return self::unpackInt($buffer, 1, 'c');
    }

    /**
     * @param resource|string $buffer
     *
     * @throws InaccessibleResourceException
     */
    public static function unpackUInt8(&$buffer): int
    {
        return self::unpackInt($buffer, 1, 'C');
    }

    /**
     * @param resource|string $buffer
     *
     * @throws InaccessibleResourceException
     */
    public static function unpackInt16(&$buffer): int
    {
        // A signed 16-bit integer can go from -32,768 to 32,768. An unsigned
        // 16-bit integer can go from 0 to 65,536. Due to limited PHP unpacking
        // abilities, we have to unpack signed integers as unsigned and then
        // mathematically convert them to signed.

        $unsigned = self::unpackUInt16($buffer);

        if ($unsigned >= (2 ** 15))
        {
            return ((2 ** 16) - $unsigned) * -1;
        }

        return $unsigned;
    }

    /**
     * @param resource|string $buffer
     *
     * @throws InaccessibleResourceException
     */
    public static function unpackUInt16(&$buffer): int
    {
        return self::unpackInt($buffer, 2, 'n');
    }

    /**
     * @param resource|string $buffer
     *
     * @throws InaccessibleResourceException
     */
    public static function unpackInt32(&$buffer): int
    {
        // See NetworkPacket::unpackInt16() for explanation as to why the manual
        // unsigned to signed conversion.

        $unsigned = self::unpackUInt32($buffer);

        if ($unsigned >= (2 ** 31))
        {
            return ((2 ** 32) - $unsigned) * -1;
        }

        return $unsigned;
    }

    /**
     * @param resource|string $buffer
     *
     * @throws InaccessibleResourceException
     */
    public static function unpackUInt32(&$buffer): int
    {
        return self::unpackInt($buffer, 4, 'N');
    }

    /**
     * @param resource|string $buffer
     *
     * @throws InaccessibleResourceException
     */
    public static function unpackInt64(&$buffer): int
    {
        return self::unpackInt($buffer, 8, 'J');
    }

    /**
     * @param resource|string $buffer
     *
     * @throws InaccessibleResourceException
     */
    public static function unpackUInt64(&$buffer): int
    {
        return self::unpackInt($buffer, 8, 'q');
    }

    /**
     * @param resource|string $buffer
     *
     * @throws InaccessibleResourceException
     */
    public static function unpackFiringInfo(&$buffer): FiringInfoData
    {
        $data = new FiringInfoData();

        $data->timeSent = NetworkPacket::unpackFloat($buffer);
        $data->shot = NetworkPacket::unpackShot($buffer);
        $data->flag = NetworkPacket::unpackString($buffer, 2);
        $data->lifetime = NetworkPacket::unpackFloat($buffer);

        return $data;
    }

    /**
     * @param resource|string $buffer
     *
     * @throws InaccessibleResourceException
     */
    public static function unpackFlag(&$buffer): FlagData
    {
        $flag = new FlagData();

        $flag->index = NetworkPacket::unpackUInt16($buffer);
        $flag->abbv = NetworkPacket::unpackString($buffer, 2);
        $flag->status = NetworkPacket::unpackUInt16($buffer);
        $flag->endurance = NetworkPacket::unpackUInt16($buffer);
        $flag->owner = NetworkPacket::unpackUInt8($buffer);
        $flag->position = NetworkPacket::unpackVector($buffer);
        $flag->launchPos = NetworkPacket::unpackVector($buffer);
        $flag->landingPos = NetworkPacket::unpackVector($buffer);
        $flag->flightTime = NetworkPacket::unpackFloat($buffer);
        $flag->flightEnd = NetworkPacket::unpackFloat($buffer);
        $flag->initialVelocity = NetworkPacket::unpackFloat($buffer);

        return $flag;
    }

    /**
     * @param resource|string $buffer
     *
     * @throws InaccessibleResourceException
     */
    public static function unpackFloat(&$buffer): float
    {
        $binary = self::safeReadResource($buffer, 4);

        return (float)unpack('G', $binary)[1];
    }

    /**
     * @param resource|string $buffer
     *
     * @throws InaccessibleResourceException
     *
     * @return array{float, float, float, float}
     */
    public static function unpack4Float(&$buffer): array
    {
        return [
            self::unpackFloat($buffer),
            self::unpackFloat($buffer),
            self::unpackFloat($buffer),
            self::unpackFloat($buffer),
        ];
    }

    /**
     * @param resource|string $buffer
     *
     * @throws InaccessibleResourceException
     *
     * @return array{float, float, float}
     */
    public static function unpackVector(&$buffer): array
    {
        return [
            self::unpackFloat($buffer),
            self::unpackFloat($buffer),
            self::unpackFloat($buffer),
        ];
    }

    /**
     * @param resource|string $buffer
     *
     * @throws InaccessibleResourceException
     */
    public static function unpackIpAddress(&$buffer): string
    {
        // This byte was reserved for differentiating between IPv4 and IPv6
        // addresses. However, since BZFlag only supports IPv4, this byte is
        // skipped.
        self::safeReadResource($buffer, 1);

        $ipAsInt = NetworkPacket::unpackUInt32($buffer);

        return long2ip($ipAsInt);
    }

    /**
     * @param resource|string $buffer
     *
     * @throws InaccessibleResourceException
     */
    public static function unpackPlayerState(&$buffer, int $code): PlayerState
    {
        $state = new PlayerState();
        $state->order = NetworkPacket::unpackInt32($buffer);
        $state->status = NetworkPacket::unpackInt16($buffer);

        if ($code === NetworkMessage::PLAYER_UPDATE)
        {
            $state->position = NetworkPacket::unpackVector($buffer);
            $state->velocity = NetworkPacket::unpackVector($buffer);
            $state->azimuth = NetworkPacket::unpackFloat($buffer);
            $state->angularVelocity = NetworkPacket::unpackFloat($buffer);
        }
        else
        {
            $pos = [
                NetworkPacket::unpackInt16($buffer),
                NetworkPacket::unpackInt16($buffer),
                NetworkPacket::unpackInt16($buffer),
            ];
            $vel = [
                NetworkPacket::unpackInt16($buffer),
                NetworkPacket::unpackInt16($buffer),
                NetworkPacket::unpackInt16($buffer),
            ];
            $azi = NetworkPacket::unpackInt16($buffer);
            $angVel = NetworkPacket::unpackInt16($buffer);

            $position = [0, 0, 0];
            $velocity = [0, 0, 0];

            for ($i = 0; $i < 3; ++$i)
            {
                $position[$i] = ((float)$pos[$i] * NetworkPacket::SMALL_MAX_DIST) / NetworkPacket::SMALL_SCALE;
                $velocity[$i] = ((float)$vel[$i] * NetworkPacket::SMALL_MAX_VEL) / NetworkPacket::SMALL_SCALE;
            }

            $state->position = $position;
            $state->velocity = $velocity;
            $state->azimuth = ((float)$azi * M_PI) / NetworkPacket::SMALL_SCALE;
            $state->angularVelocity = ((float)$angVel * NetworkPacket::SMALL_MAX_ANG_VEL) / NetworkPacket::SMALL_SCALE;
        }

        if (($state->status & PlayerState::JUMP_JETS) !== 0)
        {
            $jumpJets = NetworkPacket::unpackUInt16($buffer);
            $state->jumpJetsScale = (float)$jumpJets / NetworkPacket::SMALL_SCALE;
        }
        else
        {
            $state->jumpJetsScale = 0.0;
        }

        if (($state->status & PlayerState::ON_DRIVER) !== 0)
        {
            $state->physicsDriver = NetworkPacket::unpackInt32($buffer);
        }
        else
        {
            $state->physicsDriver = -1;
        }

        if (($state->status & PlayerState::USER_INPUTS) !== 0)
        {
            $speed = NetworkPacket::unpackUInt16($buffer);
            $angVel = NetworkPacket::unpackUInt16($buffer);

            $state->userSpeed = ((float)$speed * NetworkPacket::SMALL_MAX_VEL) / NetworkPacket::SMALL_SCALE;
            $state->userAngVel = ((float)$angVel * NetworkPacket::SMALL_MAX_ANG_VEL) / NetworkPacket::SMALL_SCALE;
        }
        else
        {
            $state->userSpeed = 0.0;
            $state->userAngVel = 0.0;
        }

        if (($state->status & PlayerState::PLAY_SOUND) !== 0)
        {
            $state->sounds = NetworkPacket::unpackUInt8($buffer);
        }
        else
        {
            $state->sounds = PlayerState::NO_SOUNDS;
        }

        return $state;
    }

    /**
     * @param resource|string $buffer
     *
     * @throws InaccessibleResourceException
     */
    public static function unpackShot(&$buffer): ShotData
    {
        $shot = new ShotData();

        $shot->playerId = NetworkPacket::unpackUInt8($buffer);
        $shot->shotId = NetworkPacket::unpackUInt16($buffer);
        $shot->position = NetworkPacket::unpackVector($buffer);
        $shot->velocity = NetworkPacket::unpackVector($buffer);
        $shot->deltaTime = NetworkPacket::unpackFloat($buffer);
        $shot->team = NetworkPacket::unpackUInt16($buffer);

        return $shot;
    }

    /**
     * @param resource|string $buffer
     *
     * @throws InaccessibleResourceException
     */
    public static function unpackString(&$buffer, int $size): string
    {
        $binary = self::safeReadResource($buffer, $size);
        $unpacked = unpack('A*', $binary);
        /** @var string $string */
        $string = $unpacked[1] ?? '';

        return trim(preg_replace('/[[:^print:]]/', '', $string) ?? '');
    }

    /**
     * @param resource|string $buffer
     *
     * @throws InaccessibleResourceException
     */
    public static function unpackStdString(&$buffer): string
    {
        $strSize = self::unpackUInt32($buffer);

        return self::unpackString($buffer, $strSize);
    }

    /**
     * @param resource|string $buffer
     *
     * @throws InaccessibleResourceException
     * @throws InvalidTimestampFormatException
     */
    public static function unpackTimestamp(&$buffer): \DateTime
    {
        $msb = NetworkPacket::unpackUInt32($buffer);
        $lsb = NetworkPacket::unpackUInt32($buffer);

        $tsRaw = ($msb << 32) + $lsb;
        $tsFloat = (float)($tsRaw / 1000000);

        $formats = ['U.u', 'U'];

        foreach ($formats as $format)
        {
            $timestamp = \DateTime::createFromFormat(
                $format,
                "{$tsFloat}",
                new \DateTimeZone('UTC')
            );

            if ($timestamp !== false)
            {
                return $timestamp;
            }
        }

        throw new InvalidTimestampFormatException('No format valid format was found for this timestamp');
    }

    /**
     * @param resource|string $buffer
     *
     * @throws InaccessibleResourceException
     */
    private static function unpackInt(&$buffer, int $size, string $symbol): int
    {
        $binary = self::safeReadResource($buffer, $size);

        return unpack($symbol, $binary)[1];
    }

    /**
     * Safely read a resource or string buffer and return a string that can be
     * passed to `unpack()`.
     *
     * @param resource|string $buffer
     *
     * @throws InaccessibleResourceException when buffer could not be read as a resource
     */
    private static function safeReadResource(&$buffer, int $size): string
    {
        if (is_resource($buffer))
        {
            if ($size < 0)
            {
                $stats = fstat($buffer);

                if ($stats === false)
                {
                    throw new InaccessibleResourceException('Could not fstat() this resource');
                }

                $size = $stats['size'];
            }

            $readValue = fread($buffer, $size);

            if ($readValue === false)
            {
                throw new InaccessibleResourceException('Failure to read buffer as resource.');
            }

            return $readValue;
        }

        if ($size < 0)
        {
            $binary = $buffer;
            $buffer = '';

            return $binary;
        }

        $binary = substr($buffer, 0, $size);
        $buffer = substr($buffer, $size);

        return $binary;
    }
}
