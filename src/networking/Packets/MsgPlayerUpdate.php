<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

use allejo\bzflag\networking\GameData\PlayerState;
use allejo\bzflag\networking\InaccessibleResourceException;

class MsgPlayerUpdate extends GamePacket
{
    public const PACKET_TYPE = 'MsgPlayerUpdate';

    /** @var int */
    private $playerId;

    /** @var PlayerState */
    private $state;

    public function getPlayerId(): int
    {
        return $this->playerId;
    }

    public function getState(): PlayerState
    {
        return $this->state;
    }

    /**
     * {@inheritdoc}
     *
     * @throws InaccessibleResourceException
     */
    protected function unpack(): void
    {
        // Discard this value; I'm not sure why this value comes out to a weird
        // float. We have the timestamp of the raw packet, so just that instead
        $_ = NetworkPacket::unpackFloat($this->buffer);

        $this->playerId = NetworkPacket::unpackUInt8($this->buffer);
        $this->state = NetworkPacket::unpackPlayerState($this->buffer, $this->packet->getCode());
    }
}
