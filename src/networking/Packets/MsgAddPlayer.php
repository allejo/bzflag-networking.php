<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

use allejo\bzflag\networking\GameData\PlayerScore;

/**
 * @since 1.0.0
 */
class MsgAddPlayer extends GamePacket
{
    public const PACKET_TYPE = 'MsgAddPlayer';

    /** @var int */
    private $playerId;

    /** @var int */
    private $playerType;

    /** @var int */
    private $teamValue;

    /** @var string */
    private $callsign;

    /** @var string */
    private $motto;

    /** @var PlayerScore */
    private $score;

    /**
     * @since 1.0.0
     * @deprecated 1.1 use `MsgAddPlayer::getPlayerId()` instead
     */
    public function getPlayerIndex(): int
    {
        trigger_deprecation('allejo/bzflag-networking.php', '1.1.0', 'Using "%s" is deprecated, use "%s" instead.', 'getPlayerIndex', 'getPlayerId');

        return $this->getPlayerId();
    }

    /**
     * @since 1.1
     */
    public function getPlayerId(): int
    {
        return $this->playerId;
    }

    /**
     * @since 1.0.0
     */
    public function getPlayerType(): int
    {
        return $this->playerType;
    }

    /**
     * @since 1.0.0
     */
    public function getTeamValue(): int
    {
        return $this->teamValue;
    }

    /**
     * @since 1.0.0
     */
    public function getCallsign(): string
    {
        return $this->callsign;
    }

    /**
     * @since 1.0.0
     */
    public function getMotto(): string
    {
        return $this->motto;
    }

    /**
     * @since 1.0.0
     */
    public function getScore(): PlayerScore
    {
        return $this->score;
    }

    /**
     * @since 1.0.0
     */
    protected function defaultComplexVariables(): void
    {
        $this->score = new PlayerScore();
    }

    /**
     * @since 1.0.0
     */
    protected function unpack(): void
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
