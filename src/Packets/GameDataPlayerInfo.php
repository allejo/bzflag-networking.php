<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

class GameDataPlayerInfo implements \JsonSerializable
{
    /** @var int */
    public $playerIndex;

    /** @var string */
    public $ipAddress;

    public function jsonSerialize()
    {
        return [
            'playerIndex' => $this->playerIndex,
            'ipAddress' => $this->ipAddress,
        ];
    }
}
