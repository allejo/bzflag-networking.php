<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

use allejo\bzflag\generic\JsonSerializePublicGetters;
use allejo\bzflag\networking\Exceptions\InaccessibleResourceException;
use allejo\bzflag\networking\Exceptions\InvalidTimestampFormatException;

/**
 * An abstraction on top of a NetworkPacket that contains actual data of packets
 * that occur in a game.
 *
 * @since 1.0.0
 */
abstract class GamePacket implements \JsonSerializable
{
    use JsonSerializePublicGetters;

    public const PACKET_TYPE = '';

    /** @var NetworkPacket */
    protected $packet;

    /** @var false|string */
    protected $buffer;

    /** @var \DateTime */
    protected $timestamp;

    /** @var \DateTime */
    protected $timestampOffset;

    /** @var array<NetworkMessage::*, class-string<GamePacket>> */
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
     * @since 1.0.0
     *
     * @throws InaccessibleResourceException
     * @throws InvalidTimestampFormatException
     * @throws PacketNotSetException
     * @throws \InvalidArgumentException
     */
    final public function __construct(?NetworkPacket $packet)
    {
        if ($packet === null)
        {
            throw new PacketNotSetException('');
        }
        $this->packet = clone $packet;
        $this->buffer = $packet->getData();
        $this->timestamp = $packet->getTimestamp();
        $this->defaultComplexVariables();
        $this->unpack();
    }

    /**
     * Get a clone of the original NetworkPacket used to create this GamePacket.
     *
     * @api
     *
     * @since 1.0.7
     */
    public function getRawPacket(): NetworkPacket
    {
        return clone $this->packet;
    }

    /**
     * @since 1.0.0
     */
    public function getPacketType(): string
    {
        return static::PACKET_TYPE;
    }

    /**
     * @since 1.0.0
     */
    public function getTimestamp(): string
    {
        return $this->timestamp->format(DATE_ATOM);
    }

    /**
     * @since 1.0.0
     */
    public function getTimestampAsDateTime(): \DateTime
    {
        return $this->timestamp;
    }

    /**
     * Create a GamePacket equivalent from a PHP resource.
     *
     * @since 1.0.0
     *
     * @param resource $resource
     *
     * @throws InvalidTimestampFormatException
     * @throws PacketInvalidException
     * @throws UnsupportedPacketException
     * @throws InaccessibleResourceException
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

    /**
     * @since 1.0.0
     *
     * @return array<int, string>
     */
    protected function getJsonEncodeBlacklist(): array
    {
        return [
            'rawPacket',
            'timestampAsDateTime',
        ];
    }

    /**
     * Initialize special instance variables without having to override the
     * constructor.
     *
     * @since 1.0.0
     */
    protected function defaultComplexVariables(): void
    {
    }

    /**
     * Unpack the NetworkPacket buffer into a GamePacket's instance variables.
     *
     * @since 1.0.0
     *
     * @throws InaccessibleResourceException
     * @throws InvalidTimestampFormatException when a timestamp cannot be unpacked correctly from the buffer
     * @throws \InvalidArgumentException
     */
    abstract protected function unpack(): void;
}
