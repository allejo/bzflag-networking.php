<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking;

use allejo\bzflag\replays\ReplayHeader as Base;

trigger_deprecation(
    'allejo/bzflag-networking.php',
    '1.1',
    'Using the "%s" class is deprecated, use "%s" instead.',
    ReplayHeader::class,
    Base::class
);

/**
 * @deprecated 1.1 use "allejo\bzflag\replays\ReplayHeader" instead
 * @since      1.0.0
 */
class ReplayHeader extends Base
{
}
