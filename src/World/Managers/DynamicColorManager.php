<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\World\Managers;

use allejo\bzflag\networking\Packets\NetworkPacket;
use allejo\bzflag\networking\World\Modifiers\DynamicColor;

class DynamicColorManager extends BaseManager
{
    /** @var array<int, DynamicColor> */
    private $colors = [];

    /**
     * @return array<int, DynamicColor>
     */
    public function getColors(): array
    {
        return $this->colors;
    }

    /**
     * @param resource|string $resource
     */
    public function unpack(&$resource): void
    {
        $count = NetworkPacket::unpackUInt32($resource);

        for ($i = 0; $i < $count; ++$i)
        {
            $color = new DynamicColor();
            $color->unpack($resource);

            $this->colors[] = $color;
        }
    }
}
