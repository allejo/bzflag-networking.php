<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\Packets;

class GameDataPlayerData implements \JsonSerializable
{
    const IsRegistered = 1 << 0;
    const IsVerified = 1 << 1;
    const IsAdmin = 1 << 2;

    public $playerId;
    public $isRegistered;
    public $isVerified;
    public $isAdmin;

    public function jsonSerialize()
    {
        return [
            'playerId' => $this->playerId,
            'isRegistered' => $this->isRegistered,
            'isVerified' => $this->isVerified,
            'isAdmin' => $this->isAdmin,
        ];
    }
}
