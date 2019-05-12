<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

use allejo\bzflag\networking\GameData\PlayerScore;

class MsgAddPlayer extends GamePacket
{
    const PACKET_TYPE = 'MsgAddPlayer';

    private $playerIndex;
    private $playerType;
    private $teamValue;
    private $callsign;
    private $motto;
    private $score;

    protected function defaultComplexVariables()
    {
        $this->score = new PlayerScore();
    }

    protected function unpack()
    {
        $this->playerIndex = NetworkPacket::unpackUInt8($this->buffer);
        $this->playerType = NetworkPacket::unpackUInt16($this->buffer);
        $this->teamValue = NetworkPacket::unpackUInt16($this->buffer);
        $this->score->wins = NetworkPacket::unpackUInt16($this->buffer);
        $this->score->losses = NetworkPacket::unpackUInt16($this->buffer);
        $this->score->teamKills = NetworkPacket::unpackUInt16($this->buffer);
        $this->callsign = NetworkPacket::unpackString($this->buffer, NetworkProtocol::CALLSIGN_LEN);
        $this->motto = NetworkPacket::unpackString($this->buffer, NetworkProtocol::MOTTO_LEN);
    }
}
