<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\GameData;

class FiringInfoData implements \JsonSerializable
{
    /** @var float */
    public $timeSent;

    /** @var ShotData */
    public $shot;

    /** @var string */
    public $flag;

    /** @var float */
    public $lifetime;

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'timeSent' => $this->timeSent,
            'shot' => $this->shot,
            'flag' => $this->flag,
            'lifetime' => $this->lifetime,
        ];
    }
}
