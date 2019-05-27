<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

class MsgGameTime extends GamePacket
{
    const PACKET_TYPE = 'MsgGameTime';

    /** @var int */
    private $msb;

    /** @var int */
    private $lsb;

    /** @var \DateTime */
    private $value;

    /**
     * @todo Remove in 2.0.0
     * @deprecated This value is only significant for calculating a timestamp. Use `getValue()` instead.
     *
     * @return int
     */
    public function getMsb(): int
    {
        return $this->msb;
    }

    /**
     * @todo Remove in 2.0.0
     * @deprecated This value is only significant for calculating a timestamp. Use `getValue()` instead.
     *
     * @return int
     */
    public function getLsb(): int
    {
        return $this->lsb;
    }

    /**
     * @return \DateTime
     */
    public function getValue(): \DateTime
    {
        return $this->value;
    }

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
