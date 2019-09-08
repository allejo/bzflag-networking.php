<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\GameData;

/**
 * @todo 2.0.0 Remove the `FiringInfoData` and then rename this class to `FiringInfoData`
 *
 * @deprecated Use the correctly spelled `FiringInfoData` class
 */
class FiringIntoData implements \JsonSerializable
{
    /** @var float */
    public $timeSent;

    /** @var ShotData */
    public $shot;

    /** @var string */
    public $flag;

    /** @var float */
    public $lifetime;

    public function jsonSerialize()
    {
        return [
            'timeSent' => $this->timeSent,
            'shot' => $this->shot,
            'flag' => $this->flag,
            'lifetime' => $this->lifetime,
        ];
    }
}
