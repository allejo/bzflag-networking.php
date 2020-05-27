<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\world\Modifiers;

class SequenceParams
{
    /** @var float */
    public $period;

    /** @var float */
    public $offset;

    /** @var null|int[] */
    public $list;

    /** @var int */
    public $count;
}