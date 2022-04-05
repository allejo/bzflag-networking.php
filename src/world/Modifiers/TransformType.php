<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\world\Modifiers;

/**
 * @since future
 */
abstract class TransformType
{
    public const SHIFT_TRANSFORM = 0;
    public const SCALE_TRANSFORM = 1;
    public const SHEAR_TRANSFORM = 2;
    public const SPIN_TRANSFORM = 3;
    public const INDEX_TRANSFORM = 4;
}
