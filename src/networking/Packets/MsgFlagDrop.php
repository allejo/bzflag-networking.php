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
class MsgFlagDrop extends GamePacket
{
    public const PACKET_TYPE = 'MsgDropFlag';

    /** @var int */
    private $playerId;

    /** @var FlagData */
    private $flag;

    /**
     * @since 1.0.0
     */
    public function getPlayerId(): int
    {
        return $this->playerId;
    }

    /**
     * @since 1.0.0
     */
    public function getFlag(): FlagData
    {
        return $this->flag;
    }

    /**
     * @since 1.0.0
     */
    protected function unpack(): void
    {
        $this->playerId = NetworkPacket::unpackUInt8($this->buffer);
        $this->flag = NetworkPacket::unpackFlag($this->buffer);
    }
}
