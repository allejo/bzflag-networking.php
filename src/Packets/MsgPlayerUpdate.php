<?php declare(strict_types=1);


namespace allejo\bzflag\networking\Packets;


class MsgPlayerUpdate extends GamePacket
{
    const PACKET_TYPE = 'MsgPlayerUpdate';

    /** @var int */
    private $playerId;

    /** @var GameDataPlayerState */
    private $state;

    protected function unpack()
    {
        // Discard this value; I'm not sure why this value comes out to a weird
        // float. We have the timestamp of the raw packet, so just that instead
        $_ = Packet::unpackFloat($this->buffer);

        $this->playerId = Packet::unpackUInt8($this->buffer);
        $this->state = Packet::unpackPlayerState($this->buffer, $this->packet->getCode());
    }
}
