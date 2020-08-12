<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking;

use allejo\bzflag\replays\Replay as Base;

@trigger_error('Using the "allejo\bzflag\networking\Replay" class is deprecated since version 1.1 and will be removed in version 2, use "allejo\bzflag\replays\Replay" instead.', E_USER_DEPRECATED);

/**
 * @deprecated since 1.1, use "allejo\bzflag\replays\Replay" instead
 * @since      1.0.0
 */
class Replay extends Base
{
}
