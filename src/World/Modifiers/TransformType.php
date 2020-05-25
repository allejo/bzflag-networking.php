<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\World\Modifiers;

abstract class TransformType
{
    const SHIFT_TRANSFORM = 0;
    const SCALE_TRANSFORM = 1;
    const SHEAR_TRANSFORM = 2;
    const SPIN_TRANSFORM = 3;
    const INDEX_TRANSFORM = 4;
}
