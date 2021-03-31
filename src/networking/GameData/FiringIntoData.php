<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\GameData;

use allejo\bzflag\networking\GameData\FiringInfoData as Base;

@trigger_error('Using the "allejo\bzflag\networking\GameData\FiringIntoData" class is deprecated since version 1.0.9 and will be removed in version 2, use "allejo\bzflag\networking\GameData\FiringInfoData" instead.', E_USER_DEPRECATED);

/**
 * @deprecated 1.0.9 use "allejo\bzflag\networking\GameData\FiringInfoData" instead
 * @since      1.0.0
 */
class FiringIntoData extends Base
{
}
