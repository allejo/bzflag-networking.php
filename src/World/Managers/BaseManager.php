<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\World\Managers;

use allejo\bzflag\networking\JsonSerializePublicGetters;
use allejo\bzflag\networking\World\WorldDatabase;

abstract class BaseManager implements \JsonSerializable
{
    use JsonSerializePublicGetters;

    /** @var WorldDatabase */
    protected $worldDatabase;

    public function __construct(WorldDatabase &$worldDatabase)
    {
        $this->worldDatabase = &$worldDatabase;
    }
}
