<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\World;

use allejo\bzflag\networking\Packets\NetworkPacket;

class DynamicColor
{
    /** @var string */
    private $name;

    /** @var array<int, ChannelParams> */
    private $channels;

    public function unpack($resource)
    {
        $this->name = NetworkPacket::unpackStdString($resource);

        for ($c = 0; $c < 4; ++$c)
        {
            $p = $this->channels[$c] = new ChannelParams();

            $p->minValue = NetworkPacket::unpackFloat($resource);
            $p->maxValue = NetworkPacket::unpackFloat($resource);

            $size = NetworkPacket::unpackUInt32($resource);
            for ($i = 0; $i < $size; ++$i)
            {
                $p->sinusoids[$i] = new SinusoidParams();
                $p->sinusoids[$i]->period = NetworkPacket::unpackFloat($resource);
                $p->sinusoids[$i]->offset = NetworkPacket::unpackFloat($resource);
                $p->sinusoids[$i]->weight = NetworkPacket::unpackFloat($resource);
            }

            $size = NetworkPacket::unpackUInt32($resource);
            for ($i = 0; $i < $size; ++$i)
            {
                $p->clampUps[$i] = new ClampParams();
                $p->clampUps[$i]->period = NetworkPacket::unpackFloat($resource);
                $p->clampUps[$i]->offset = NetworkPacket::unpackFloat($resource);
                $p->clampUps[$i]->width = NetworkPacket::unpackFloat($resource);
            }

            $size = NetworkPacket::unpackUInt32($resource);
            for ($i = 0; $i < $size; ++$i)
            {
                $p->clampDowns[$i] = new ClampParams();
                $p->clampDowns[$i]->period = NetworkPacket::unpackFloat($resource);
                $p->clampDowns[$i]->offset = NetworkPacket::unpackFloat($resource);
                $p->clampDowns[$i]->width = NetworkPacket::unpackFloat($resource);
            }

            $size = NetworkPacket::unpackUInt32($resource);
            $p->sequence->count = $size;
            if ($size > 0)
            {
                $p->sequence->period = NetworkPacket::unpackFloat($resource);
                $p->sequence->offset = NetworkPacket::unpackFloat($resource);

                $p->sequence->list = [];
                for ($i = 0; $i < $size; ++$i)
                {
                    $p->sequence->list[$i] = NetworkPacket::unpackUInt8($resource);
                }
            }
            else
            {
                $p->sequence->list = null;
            }
        }
    }
}
