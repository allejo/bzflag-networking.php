<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

use allejo\bzflag\networking\GameData\BZDBSetting;

/**
 * @since 1.0.0
 */
class MsgSetVar extends GamePacket
{
    public const PACKET_TYPE = 'MsgSetVar';

    /** @var BZDBSetting[] */
    private $settings = [];

    /**
     * @since 1.0.0
     *
     * @return BZDBSetting[]
     */
    public function getSettings(): array
    {
        return $this->settings;
    }

    /**
     * @since 1.0.0
     */
    protected function unpack(): void
    {
        $count = NetworkPacket::unpackUInt16($this->buffer);

        for ($i = 0; $i < $count; ++$i)
        {
            $setting = new BZDBSetting();

            $nameLength = NetworkPacket::unpackUInt8($this->buffer);
            $setting->name = NetworkPacket::unpackString($this->buffer, $nameLength);

            $valueLength = NetworkPacket::unpackUInt8($this->buffer);
            $setting->value = NetworkPacket::unpackString($this->buffer, $valueLength);

            $this->settings[] = $setting;
        }
    }
}
