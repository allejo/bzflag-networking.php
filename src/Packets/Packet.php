<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

class Packet implements IUnpackable
{
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

        if ($buffer === false)
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

    public static function unpackString(&$buffer, int $size): string
    {
        $binary = self::safeReadResource($buffer, $size);

        return unpack('A*', $binary)[1];
    }

    public static function unpackTimestamp(&$buffer): \DateTime
    {
        $msb = Packet::unpackUInt32($buffer);
        $lsb = Packet::unpackUInt32($buffer);

        $tsRaw = ($msb << 32) + $lsb;
        $tsFloat = (float)($tsRaw / 1000000);

        return \DateTime::createFromFormat(
            'U.u',
            "$tsFloat",
            new \DateTimeZone('UTC')
        );
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
            return fread($buffer, $size);
        }

        $binary = substr($buffer, 0, $size);
        $buffer = substr($buffer, $size);

        return $binary;
    }
}
