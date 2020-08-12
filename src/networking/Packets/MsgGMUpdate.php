<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

use allejo\bzflag\networking\GameData\ShotData;

/**
 * @since 1.0.0
 */
class MsgGMUpdate extends GamePacket
{
    public const PACKET_TYPE = 'MsgGMUpdate';

    /** @var int */
    private $target;

    /** @var ShotData */
    private $shot;

    /**
     * @since 1.0.0
     */
    public function getTarget(): int
    {
        return $this->target;
    }

    /**
     * @since 1.0.0
     */
    public function getShot(): ShotData
    {
        return $this->shot;
    }

    /**
     * @since 1.0.0
     */
    protected function unpack(): void
    {
        $this->shot = NetworkPacket::unpackShot($this->buffer);
        $this->target = NetworkPacket::unpackUInt8($this->buffer);
    }
}
