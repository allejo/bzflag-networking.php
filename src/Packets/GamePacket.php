<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

abstract class GamePacket
{
    const PACKET_TYPE = '';

    /** @var Packet */
    protected $packet;

    /** @var string */
    protected $buffer;

    /** @var \DateTime */
    protected $timestamp;

    /** @var \DateTime */
    protected $timestampOffset;

    private static $mapping = [
        NetworkMessage::AddPlayer => MsgAddPlayer::class,
        NetworkMessage::AdminInfo => MsgAdminInfo::class,
        NetworkMessage::Alive => MsgAlive::class,
        NetworkMessage::CaptureFlag => MsgCaptureFlag::class,
        NetworkMessage::DropFlag => MsgFlagDrop::class,
        NetworkMessage::GrabFlag => MsgFlagGrab::class,
        NetworkMessage::FlagUpdate => MsgFlagUpdate::class,
        NetworkMessage::GameTime => MsgGameTime::class,
        NetworkMessage::GMUpdate => MsgGMUpdate::class,
        NetworkMessage::Killed => MsgKilled::class,
        NetworkMessage::Message => MsgMessage::class,
        NetworkMessage::NewRabbit => MsgNewRabbit::class,
        NetworkMessage::Null => MsgNull::class,
        NetworkMessage::Pause => MsgPause::class,
        NetworkMessage::PlayerInfo => MsgPlayerInfo::class,
        NetworkMessage::PlayerUpdate => MsgPlayerUpdate::class,
    ];

    /**
     * @param Packet $packet
     *
     * @throws PacketNotSetException
     */
    public function __construct(Packet $packet)
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
     * @throws UnsupportedPacket
     * @throws PacketInvalidException
     *
     * @return GamePacket
     */
    public static function fromResource($resource)
    {
        $packet = new Packet($resource);
        $msgCode = $packet->getCode();

        if (!isset(self::$mapping[$msgCode]))
        {
            throw new UnsupportedPacket(
                sprintf('Unsupported game packet code: %s', NetworkMessage::charsFromCode($msgCode))
            );
        }

        $gamePacket = self::$mapping[$msgCode];

        return new $gamePacket($packet);
    }
}
