<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\GameData;

class PlayerInfo implements \JsonSerializable
{
    /** @var int */
    public $playerIndex;

    /** @var string */
    public $ipAddress;

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'playerIndex' => $this->playerIndex,
            'ipAddress' => $this->ipAddress,
        ];
    }
}