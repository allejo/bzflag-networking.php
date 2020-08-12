<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\world\Managers;

use allejo\bzflag\generic\JsonSerializePublicGetters;
use allejo\bzflag\world\WorldDatabase;

/**
 * @since future
 */
abstract class BaseManager implements \JsonSerializable
{
    use JsonSerializePublicGetters;

    /** @var WorldDatabase */
    protected $worldDatabase;

    /**
     * @since future
     */
    public function __construct(WorldDatabase $worldDatabase)
    {
        $this->worldDatabase = $worldDatabase;
    }
}
