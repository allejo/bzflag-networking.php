<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

/**
 * @deprecated since 1.1, this interface no longer has a purpose and should not be relied upon outside of this library
 *
 * @todo Remove in 2.0
 */
interface Unpackable
{
    /**
     * Unpackable constructor.
     *
     * @param resource|string $resource
     */
    public function __construct($resource);
}
