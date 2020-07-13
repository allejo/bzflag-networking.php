<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\world\Object;

abstract class TeleporterLinkLocation
{
    public const FRONT = 0;
    public const BACK = 1;

    /**
     * @throws \InvalidArgumentException when an invalid BZW teleporter location is given
     *
     * @return self::*
     */
    public static function fromBZW(string $direction): int
    {
        $direction = strtolower($direction);

        if ($direction === 'f')
        {
            return self::FRONT;
        }

        if ($direction === 'b')
        {
            return self::BACK;
        }

        throw new \InvalidArgumentException("Unknown teleporter link direction: {$direction}");
    }
}
