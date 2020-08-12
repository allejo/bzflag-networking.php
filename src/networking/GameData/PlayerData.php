<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\GameData;

/**
 * @since 1.0.0
 */
class PlayerData implements \JsonSerializable
{
    const IS_REGISTERED = 1 << 0;
    const IS_VERIFIED = 1 << 1;
    const IS_ADMIN = 1 << 2;

    /** @var int */
    public $playerId;

    /** @var bool */
    public $isRegistered;

    /** @var bool */
    public $isVerified;

    /** @var bool */
    public $isAdmin;

    /**
     * @since 1.0.0
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'playerId' => $this->playerId,
            'isRegistered' => $this->isRegistered,
            'isVerified' => $this->isVerified,
            'isAdmin' => $this->isAdmin,
        ];
    }
}
