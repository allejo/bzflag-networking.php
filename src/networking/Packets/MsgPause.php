<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

use allejo\bzflag\networking\InaccessibleResourceException;

class MsgPause extends GamePacket
{
    public const PACKET_TYPE = 'MsgPause';

    /** @var int */
    private $playerId;

    /** @var int */
    private $paused;

    public function getPlayerId(): int
    {
        return $this->playerId;
    }

    public function getPaused(): int
    {
        return $this->paused;
    }

    /**
     * {@inheritdoc}
     *
     * @throws InaccessibleResourceException
     */
    protected function unpack(): void
    {
        $this->playerId = NetworkPacket::unpackUInt8($this->buffer);
        $this->paused = NetworkPacket::unpackUInt8($this->buffer);
    }
}
