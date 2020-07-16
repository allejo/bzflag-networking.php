<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\world\Object;

class FlagType
{
    /** @var string */
    public $abbv = '';

    /** @var FlagType */
    private static $flag;

    public static function NullFlag(): FlagType
    {
        if (self::$flag === null)
        {
            self::$flag = new self();
        }

        return self::$flag;
    }
}
