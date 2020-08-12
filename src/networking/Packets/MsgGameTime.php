<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

/**
 * @since 1.0.0
 */
class MsgGameTime extends GamePacket
{
    public const PACKET_TYPE = 'MsgGameTime';

    /** @var int */
    private $msb;

    /** @var int */
    private $lsb;

    /** @var \DateTime */
    private $value;

    /**
     * @deprecated This value is only significant for calculating a timestamp. Use `getValue()` instead.
     * @since      1.0.0
     *
     * @todo       Remove in 2.0.0
     */
    public function getMsb(): int
    {
        return $this->msb;
    }

    /**
     * @deprecated This value is only significant for calculating a timestamp. Use `getValue()` instead.
     * @since      1.0.0
     *
     * @todo       Remove in 2.0.0
     */
    public function getLsb(): int
    {
        return $this->lsb;
    }

    /**
     * @since 1.0.4
     */
    public function getValue(): \DateTime
    {
        return $this->value;
    }

    /**
     * @since 1.0.0
     */
    protected function unpack(): void
    {
        // @TODO In 2.0.0, remove the BC code

        // Get these values and store them for backward compatibility
        $buffer = $this->buffer;
        $this->msb = NetworkPacket::unpackUInt32($buffer);
        $this->lsb = NetworkPacket::unpackUInt32($buffer);

        $this->value = NetworkPacket::unpackTimestamp($this->buffer);
    }
}
