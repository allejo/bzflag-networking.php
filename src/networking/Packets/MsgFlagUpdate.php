<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

use allejo\bzflag\networking\GameData\FlagData;

/**
 * @since 1.0.0
 */
class MsgFlagUpdate extends GamePacket
{
    public const PACKET_TYPE = 'MsgFlagUpdate';

    /** @var FlagData[] */
    private $flags;

    /**
     * @since 1.0.0
     *
     * @return FlagData[]
     */
    public function getFlags(): array
    {
        return $this->flags;
    }

    /**
     * @since 1.0.0
     */
    protected function unpack(): void
    {
        $count = NetworkPacket::unpackUInt16($this->buffer);

        for ($i = 0; $i < $count; ++$i)
        {
            $this->flags[] = NetworkPacket::unpackFlag($this->buffer);
        }
    }
}
