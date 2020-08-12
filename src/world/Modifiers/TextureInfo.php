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
class TextureInfo
{
    /** @var string */
    public $name;

    /** @var string */
    public $localName;

    /** @var int */
    public $matrix;

    /** @var int */
    public $combineMode;

    /** @var bool */
    public $useAlpha;

    /** @var bool */
    public $useColor;

    /** @var bool */
    public $useSphereMap;
}
