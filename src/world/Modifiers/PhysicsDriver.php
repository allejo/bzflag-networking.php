<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\world\Modifiers;

use allejo\bzflag\generic\JsonSerializePublicGetters;
use allejo\bzflag\networking\Exceptions\InaccessibleResourceException;
use allejo\bzflag\networking\Packets\NetworkPacket;

class PhysicsDriver implements \JsonSerializable
{
    use JsonSerializePublicGetters;

    /** @var string */
    private $name;

    /** @var array<int, float> */
    private $linear;

    /** @var float */
    private $angularVel;

    /** @var array<int, float> */
    private $angularPos;

    /** @var float */
    private $radialVel;

    /** @var array<int, float> */
    private $radialPos;

    /** @var float */
    private $slideTime;

    /** @var string */
    private $deathMessage;

    public function __construct()
    {
        $this->angularPos = [];
        $this->radialPos = [];
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array<int, float>
     */
    public function getLinear(): array
    {
        return $this->linear;
    }

    public function getAngularVel(): float
    {
        return $this->angularVel;
    }

    /**
     * @return array<int, float>
     */
    public function getAngularPos(): array
    {
        return $this->angularPos;
    }

    public function getRadialVel(): float
    {
        return $this->radialVel;
    }

    /**
     * @return array<int, float>
     */
    public function getRadialPos(): array
    {
        return $this->radialPos;
    }

    public function getSlideTime(): float
    {
        return $this->slideTime;
    }

    public function getDeathMessage(): string
    {
        return $this->deathMessage;
    }

    /**
     * @param resource|string $resource
     *
     * @throws InaccessibleResourceException
     */
    public function unpack(&$resource): void
    {
        $this->name = NetworkPacket::unpackStdString($resource);

        $this->linear = NetworkPacket::unpackVector($resource);
        $this->angularVel = NetworkPacket::unpackFloat($resource);
        $this->angularPos[0] = NetworkPacket::unpackFloat($resource);
        $this->angularPos[1] = NetworkPacket::unpackFloat($resource);
        $this->radialVel = NetworkPacket::unpackFloat($resource);
        $this->radialPos[0] = NetworkPacket::unpackFloat($resource);
        $this->radialPos[1] = NetworkPacket::unpackFloat($resource);

        $this->slideTime = NetworkPacket::unpackFloat($resource);
        $this->deathMessage = NetworkPacket::unpackStdString($resource);
    }
}
