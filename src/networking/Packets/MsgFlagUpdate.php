<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

use allejo\bzflag\networking\GameData\FlagData;
use allejo\bzflag\networking\InaccessibleResourceException;

class MsgFlagUpdate extends GamePacket
{
    public const PACKET_TYPE = 'MsgFlagUpdate';

    /** @var FlagData[] */
    private $flags;

    /**
     * @return FlagData[]
     */
    public function getFlags(): array
    {
        return $this->flags;
    }

    /**
     * {@inheritdoc}
     *
     * @throws InaccessibleResourceException
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
