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

class MsgFlagDrop extends GamePacket
{
    public const PACKET_TYPE = 'MsgDropFlag';

    /** @var int */
    private $playerId;

    /** @var FlagData */
    private $flag;

    public function getPlayerId(): int
    {
        return $this->playerId;
    }

    public function getFlag(): FlagData
    {
        return $this->flag;
    }

    /**
     * {@inheritdoc}
     *
     * @throws InaccessibleResourceException
     */
    protected function unpack(): void
    {
        $this->playerId = NetworkPacket::unpackUInt8($this->buffer);
        $this->flag = NetworkPacket::unpackFlag($this->buffer);
    }
}
