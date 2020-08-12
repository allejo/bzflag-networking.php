<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

use allejo\bzflag\networking\GameData\FiringInfoData;

/**
 * @since 1.0.0
 */
class MsgShotBegin extends GamePacket
{
    public const PACKET_TYPE = 'MsgShotBegin';

    /** @var FiringInfoData */
    private $firingInfo;

    /**
     * @since 1.0.0
     */
    public function getFiringInfo(): FiringInfoData
    {
        return $this->firingInfo;
    }

    /**
     * @since 1.0.0
     */
    protected function unpack(): void
    {
        $this->firingInfo = NetworkPacket::unpackFiringInfo($this->buffer);
    }
}
