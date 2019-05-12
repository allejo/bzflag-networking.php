<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

use allejo\bzflag\networking\GameData\FiringIntoData;
use allejo\bzflag\networking\GameData\FlagData;
use allejo\bzflag\networking\GameData\PlayerState;
use allejo\bzflag\networking\GameData\ShotData;

class Packet implements IUnpackable
{
    const SmallScale = 32766.0;
    const SmallMaxDist = 0.02 * Packet::SmallScale;
    const SmallMaxVel = 0.01 * Packet::SmallScale;
    const SmallMaxAngVel = 0.001 * Packet::SmallScale;

    private $mode = -1;
    private $code = -1;
    private $length = -1;
    private $nextFilePos = -1;
    private $prevFilePos = -1;
    private $timestamp = null;
    private $data = null;

    /**
     * @param resource $resource
     *
     * @throws PacketInvalidException
     */
    public function __construct($resource)
    {
        $buffer = fread($resource, 32);

        if (feof($resource) || $buffer === false)
        {
            throw new PacketInvalidException('The given resource can no longer be read.');
        }

        $this->mode = Packet::unpackUInt16($buffer);
        $this->code = Packet::unpackUInt16($buffer);
        $this->length = Packet::unpackUInt32($buffer);
        $this->nextFilePos = Packet::unpackUInt32($buffer);
        $this->prevFilePos = Packet::unpackUInt32($buffer);
        $this->timestamp = Packet::unpackTimestamp($buffer);

        if ($this->length > 0)
        {
            $this->data = fread($resource, $this->length);
        }
    }

    /**
     * @return int
     */
    public function getMode(): int
    {
        return $this->mode;
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @return int
     */
    public function getLength(): int
    {
        return $this->length;
    }

    /**
     * @return int
     */
    public function getNextFilePos(): int
    {
        return $this->nextFilePos;
    }

    /**
     * @return int
     */
    public function getPrevFilePos(): int
    {
        return $this->prevFilePos;
    }

    /**
     * @return \DateTime|null
     */
    public function getTimestamp(): ?\DateTime
    {
        return clone $this->timestamp;
    }

    /**
     * @return string|null
     */
    public function getData(): ?string
    {
        return $this->data;
    }

    public static function unpackInt8(&$buffer): int
    {
        return self::unpackInt($buffer, 1, 'c');
    }

    public static function unpackUInt8(&$buffer): int
    {
        return self::unpackInt($buffer, 1, 'C');
    }

    public static function unpackInt16(&$buffer): int
    {
        return self::unpackInt($buffer, 2, 's');
    }

    public static function unpackUInt16(&$buffer): int
    {
        return self::unpackInt($buffer, 2, 'n');
    }

    public static function unpackInt32(&$buffer): int
    {
        return self::unpackInt($buffer, 4, 'l');
    }

    public static function unpackUInt32(&$buffer): int
    {
        return self::unpackInt($buffer, 4, 'N');
    }

    public static function unpackInt64(&$buffer): int
    {
        return self::unpackInt($buffer, 8, 'J');
    }

    public static function unpackUInt64(&$buffer): int
    {
        return self::unpackInt($buffer, 8, 'q');
    }

    private static function unpackInt(&$buffer, int $size, string $symbol): int
    {
        $binary = self::safeReadResource($buffer, $size);

        return unpack($symbol, $binary)[1];
    }

    public static function unpackFiringInfo(&$buffer): FiringIntoData
    {
        $data = new FiringIntoData();

        $data->timeSent = Packet::unpackFloat($buffer);
        $data->shot = Packet::unpackShot($buffer);
        $data->flag = Packet::unpackString($buffer, 2);
        $data->lifetime = Packet::unpackFloat($buffer);

        return $data;
    }

    public static function unpackFlag(&$buffer): ?FlagData
    {
        $flag = new FlagData();

        $flag->index = Packet::unpackUInt16($buffer);
        $flag->abbv = Packet::unpackString($buffer, 2);
        $flag->status = Packet::unpackUInt16($buffer);
        $flag->endurance = Packet::unpackUInt16($buffer);
        $flag->owner = Packet::unpackUInt8($buffer);
        $flag->position = Packet::unpackVector($buffer);
        $flag->launchPos = Packet::unpackVector($buffer);
        $flag->landingPos = Packet::unpackVector($buffer);
        $flag->flightTime = Packet::unpackFloat($buffer);
        $flag->flightEnd = Packet::unpackFloat($buffer);
        $flag->initialVelocity = Packet::unpackFloat($buffer);

        return $flag;
    }

    public static function unpackFloat(&$buffer): float
    {
        $binary = self::safeReadResource($buffer, 4);

        return (float)unpack('G', $binary)[1];
    }

    /**
     * @param resource|string $buffer
     *
     * @return float[]
     */
    public static function unpackVector(&$buffer): array
    {
        return [
            self::unpackFloat($buffer),
            self::unpackFloat($buffer),
            self::unpackFloat($buffer),
        ];
    }

    public static function unpackIpAddress(&$buffer): string
    {
        // This byte was reserved for differentiating between IPv4 and IPv6
        // addresses. However, since BZFlag only supports IPv4, this byte is
        // skipped.
        self::safeReadResource($buffer, 1);

        $ipAsInt = Packet::unpackUInt32($buffer);

        return long2ip($ipAsInt);
    }

    public static function unpackPlayerState(&$buffer, int $code): PlayerState
    {
        // @TODO see if `in_order` is necessary of if it can be removed safely
        $inOrder = Packet::unpackUInt32($buffer);
        $inStatus = Packet::unpackUInt16($buffer);

        $state = new PlayerState();

        if ($code === NetworkMessage::PlayerUpdate)
        {
            $state->position = Packet::unpackVector($buffer);
            $state->velocity = Packet::unpackVector($buffer);
            $state->azimuth = Packet::unpackFloat($buffer);
            $state->angularVelocity = Packet::unpackFloat($buffer);
        }
        else
        {
            $pos = [
                Packet::unpackInt16($buffer),
                Packet::unpackInt16($buffer),
                Packet::unpackInt16($buffer),
            ];
            $vel = [
                Packet::unpackInt16($buffer),
                Packet::unpackInt16($buffer),
                Packet::unpackInt16($buffer),
            ];
            $azi = Packet::unpackInt16($buffer);
            $angVel = Packet::unpackInt16($buffer);

            $position = [0, 0, 0];
            $velocity = [0, 0, 0];

            for ($i = 0; $i < 3; ++$i)
            {
                $position[$i] = ((float)$pos[$i] * Packet::SmallMaxDist) / Packet::SmallScale;
                $velocity[$i] = ((float)$vel[$i] * Packet::SmallMaxVel) / Packet::SmallScale;
            }

            $state->position = $position;
            $state->velocity = $velocity;
            $state->azimuth = ((float)$azi * M_PI) / Packet::SmallScale;
            $state->angularVelocity = ((float)$angVel * Packet::SmallMaxAngVel) / Packet::SmallScale;
        }

        if (($inStatus & PlayerState::JumpJets) !== 0)
        {
            $jumpJets = Packet::unpackUInt16($buffer);
            $state->jumpJetsScale = (float)$jumpJets / Packet::SmallScale;
        }

        if (($inStatus & PlayerState::OnDriver) !== 0)
        {
            // @TODO this value is being read in as unsigned, but needs to be converted to signed...?
            // @TODO fix this...
            $state->physicsDriver = Packet::unpackUInt32($buffer);
        }

        if (($inStatus & PlayerState::UserInputs) !== 0)
        {
            $speed = Packet::unpackUInt16($buffer);
            $angVel = Packet::unpackUInt16($buffer);

            $state->userSpeed = ((float)$speed * Packet::SmallMaxVel) / Packet::SmallScale;
            $state->userAngVel = ((float)$angVel * Packet::SmallMaxAngVel) / Packet::SmallScale;
        }

        if (($inStatus & PlayerState::PlaySound) !== 0)
        {
            $state->sounds = Packet::unpackUInt8($buffer);
        }

        return $state;
    }

    public static function unpackShot(&$buffer): ShotData
    {
        $shot = new ShotData();

        $shot->playerId = Packet::unpackUInt8($buffer);
        $shot->shotId = Packet::unpackUInt16($buffer);
        $shot->position = Packet::unpackVector($buffer);
        $shot->velocity = Packet::unpackVector($buffer);
        $shot->deltaTime = Packet::unpackFloat($buffer);
        $shot->team = Packet::unpackUInt16($buffer);

        return $shot;
    }

    public static function unpackString(&$buffer, int $size): string
    {
        $binary = self::safeReadResource($buffer, $size);

        return trim(unpack('A*', $binary)[1]);
    }

    /**
     * @param resource|string $buffer
     *
     * @throws \Exception
     *
     * @return \DateTime
     */
    public static function unpackTimestamp(&$buffer): \DateTime
    {
        $msb = Packet::unpackUInt32($buffer);
        $lsb = Packet::unpackUInt32($buffer);

        $tsRaw = ($msb << 32) + $lsb;
        $tsFloat = (float)($tsRaw / 1000000);

        $formats = ['U.u', 'U'];

        foreach ($formats as $format)
        {
            $timestamp = \DateTime::createFromFormat(
                $format,
                "$tsFloat",
                new \DateTimeZone('UTC')
            );

            if ($timestamp !== false)
            {
                return $timestamp;
            }
        }

        throw new \Exception('No format valid format was found for this timestamp');
    }

    /**
     * Safely read a resource or string buffer and return a string that can be
     * passed to `unpack()`.
     *
     * @param resource|string $buffer
     * @param int             $size
     *
     * @return string
     */
    private static function safeReadResource(&$buffer, int $size): string
    {
        if (is_resource($buffer))
        {
            if ($size < 0)
            {
                $stats = fstat($buffer);
                $size = $stats['size'];
            }

            return fread($buffer, $size);
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
