<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

class MsgCaptureFlag extends GamePacket
{
    const PACKET_TYPE = 'MsgCaptureFlag';

    /** @var int */
    private $playerId;

    /** @var int */
    private $flagId;

    /** @var int */
    private $team;

    /**
     * @return int
     */
    public function getPlayerId(): int
    {
        return $this->playerId;
    }

    /**
     * @return int
     */
    public function getFlagId(): int
    {
        return $this->flagId;
    }

    /**
     * @return int
     */
    public function getTeam(): int
    {
        return $this->team;
    }

    protected function unpack(): void
    {
        $this->playerId = NetworkPacket::unpackUInt8($this->buffer);
        $this->flagId = NetworkPacket::unpackUInt16($this->buffer);
        $this->team = NetworkPacket::unpackUInt16($this->buffer);
    }
}
