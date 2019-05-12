<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

use allejo\bzflag\networking\GameData\BZDBSetting;

class MsgSetVar extends GamePacket
{
    public const PACKET_TYPE = 'MsgSetVar';

    /** @var BZDBSetting[] */
    private $settings = [];

    protected function unpack()
    {
        $count = Packet::unpackUInt16($this->buffer);

        for ($i = 0; $i < $count; ++$i)
        {
            $setting = new BZDBSetting();

            $nameLength = Packet::unpackUInt8($this->buffer);
            $setting->name = Packet::unpackString($this->buffer, $nameLength);

            $valueLength = Packet::unpackUInt8($this->buffer);
            $setting->value = Packet::unpackString($this->buffer, $valueLength);

            $this->settings[] = $setting;
        }
    }
}
