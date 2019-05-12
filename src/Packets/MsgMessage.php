<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

class MsgMessage extends GamePacket
{
    const PACKET_TYPE = 'MsgMessage';

    /** @var int */
    private $playerFromId;

    /** @var int */
    private $playerToId;

    /** @var string */
    private $message;

    protected function unpack()
    {
        $this->playerFromId = NetworkPacket::unpackUInt8($this->buffer);
        $this->playerToId = NetworkPacket::unpackUInt8($this->buffer);
        $this->message = NetworkPacket::unpackString($this->buffer, -1);
    }
}
