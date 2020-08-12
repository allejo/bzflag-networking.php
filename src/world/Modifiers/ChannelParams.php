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
class ChannelParams
{
    /** @var float */
    public $minValue;

    /** @var float */
    public $maxValue;

    /** @var float */
    public $totalWeight;

    /** @var SequenceParams */
    public $sequence;

    /** @var array<int, SinusoidParams> */
    public $sinusoids = [];

    /** @var array<int, ClampParams> */
    public $clampUps = [];

    /** @var array<int, ClampParams> */
    public $clampDowns = [];

    /**
     * @since future
     */
    public function __construct()
    {
        $this->sequence = new SequenceParams();
    }
}
