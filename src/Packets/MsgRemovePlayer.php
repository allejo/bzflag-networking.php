<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

class MsgRemovePlayer extends GamePacket
{
    public const PACKET_TYPE = 'MsgRemovePlayer';

    /** @var int */
    private $playerId;

    public function getPlayerId(): int
    {
        return $this->playerId;
    }

    protected function unpack(): void
    {
        $this->playerId = NetworkPacket::unpackUInt8($this->buffer);
    }
}
