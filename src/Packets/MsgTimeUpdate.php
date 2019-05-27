<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

class MsgTimeUpdate extends GamePacket
{
    public const PACKET_TYPE = 'MsgTimeUpdate';

    /** @var int */
    private $timeLeft;

    /**
     * @return int
     */
    public function getTimeLeft(): int
    {
        return $this->timeLeft;
    }

    protected function unpack(): void
    {
        // FIXME: Is there a more robust solution to this?

        // BZFS packs this as a signed 32-bit int (https://git.io/fjRnG).
        // However, PHP doesn't have a symbol to unpack this without being
        // machine-dependent for byte order and size.
        //
        // See: https://www.php.net/manual/en/function.pack.php

        // This hack unpacks the data as both uint_32 and int_32 and then checks
        // which value is closest to 0. If the number is unpacked incorrectly,
        // it'll have a ridiculously high or low value. So we use that to our
        // advantage to see which one is more realistic. This is only accurate
        // because BZFS should never have such an extreme value for this packet.

        $buf1 = $this->buffer;
        $buf2 = $this->buffer;

        $int = NetworkPacket::unpackUInt32($buf1);
        $uint = NetworkPacket::unpackInt32($buf2);

        $this->timeLeft = abs($int) < abs($uint) ? $int : $uint;
    }
}
