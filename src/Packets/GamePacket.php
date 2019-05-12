<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

/**
 * An abstraction on top of a NetworkPacket that contains actual data of packets
 * that occur in a game.
 */
abstract class GamePacket
{
    const PACKET_TYPE = '';

    /** @var NetworkPacket */
    protected $packet;

    /** @var string */
    protected $buffer;

    /** @var \DateTime */
    protected $timestamp;

    /** @var \DateTime */
    protected $timestampOffset;

    private static $mapping = [
        NetworkMessage::ADD_PLAYER => MsgAddPlayer::class,
        NetworkMessage::ADMIN_INFO => MsgAdminInfo::class,
        NetworkMessage::ALIVE => MsgAlive::class,
        NetworkMessage::CAPTURE_FLAG => MsgCaptureFlag::class,
        NetworkMessage::DROP_FLAG => MsgFlagDrop::class,
        NetworkMessage::GRAB_FLAG => MsgFlagGrab::class,
        NetworkMessage::FLAG_UPDATE => MsgFlagUpdate::class,
        NetworkMessage::GAME_TIME => MsgGameTime::class,
        NetworkMessage::GM_UPDATE => MsgGMUpdate::class,
        NetworkMessage::KILLED => MsgKilled::class,
        NetworkMessage::MESSAGE => MsgMessage::class,
        NetworkMessage::NEW_RABBIT => MsgNewRabbit::class,
        NetworkMessage::NULL => MsgNull::class,
        NetworkMessage::PAUSE => MsgPause::class,
        NetworkMessage::PLAYER_INFO => MsgPlayerInfo::class,
        NetworkMessage::PLAYER_UPDATE => MsgPlayerUpdate::class,
        NetworkMessage::PLAYER_UPDATE_SMALL => MsgPlayerUpdate::class,
        NetworkMessage::REMOVE_PLAYER => MsgRemovePlayer::class,
        NetworkMessage::SCORE => MsgScore::class,
        NetworkMessage::SCORE_OVER => MsgScoreOver::class,
        NetworkMessage::SET_VAR => MsgSetVar::class,
        NetworkMessage::SHOT_BEGIN => MsgShotBegin::class,
        NetworkMessage::SHOT_END => MsgShotEnd::class,
        NetworkMessage::TEAM_UPDATE => MsgTeamUpdate::class,
        NetworkMessage::TELEPORT => MsgTeleport::class,
        NetworkMessage::TIME_UPDATE => MsgTimeUpdate::class,
        NetworkMessage::TRANSFER_FLAG => MsgTransferFlag::class,
    ];

    /**
     * @param NetworkPacket $packet
     *
     * @throws PacketNotSetException
     */
    public function __construct(NetworkPacket $packet)
    {
        if ($packet === null)
        {
            throw new PacketNotSetException('');
        }

        $this->packet = $packet;
        $this->buffer = $this->packet->getData();
        $this->timestamp = $this->packet->getTimestamp();

        $this->defaultComplexVariables();
        $this->unpack();
    }

    protected function defaultComplexVariables()
    {
    }

    abstract protected function unpack();

    /**
     * @param resource $resource
     *
     * @throws PacketInvalidException
     * @throws UnsupportedPacketException
     *
     * @return GamePacket
     */
    public static function fromResource($resource)
    {
        $packet = new NetworkPacket($resource);
        $msgCode = $packet->getCode();

        if (!isset(self::$mapping[$msgCode]))
        {
            throw new UnsupportedPacketException(
                sprintf('Unsupported game packet code: %s', NetworkMessage::charsFromCode($msgCode))
            );
        }

        $gamePacket = self::$mapping[$msgCode];

        return new $gamePacket($packet);
    }
}
