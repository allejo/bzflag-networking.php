<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\world\Modifiers;

use allejo\bzflag\generic\JsonSerializePublicGetters;
use allejo\bzflag\networking\InaccessibleResourceException;
use allejo\bzflag\networking\Packets\NetworkPacket;

class TextureMatrix implements \JsonSerializable
{
    use JsonSerializePublicGetters;

    /** @var string */
    private $name = "";

    /** @var bool */
    private $useStatic = false;

    /** @var bool */
    private $useDynamic = false;

    //
    // Static Properties
    //

    /** @var float */
    private $rotation = 0.0;

    /** @var float */
    private $uFixedShift = 0.0;

    /** @var float */
    private $vFixedShift = 0.0;

    /** @var float */
    private $uFixedScale = 1.0;

    /** @var float */
    private $vFixedScale = 1.0;

    /** @var float */
    private $uFixedCenter = 0.5;

    /** @var float */
    private $vFixedCenter = 0.5;

    //
    // Dynamic Properties
    //

    /** @var float */
    private $spinFreq = 0.0;

    /** @var float */
    private $uShiftFreq = 0.0;

    /** @var float */
    private $vShiftFreq = 0.0;

    /** @var float */
    private $uScaleFreq = 0.0;

    /** @var float */
    private $vScaleFreq = 0.0;

    /** @var float */
    private $uScale = 1.0;

    /** @var float */
    private $vScale = 1.0;

    /** @var float */
    private $uCenter = 0.5;

    /** @var float */
    private $vCenter = 0.5;

    public function getName(): string
    {
        return $this->name;
    }

    public function isUseStatic(): bool
    {
        return $this->useStatic;
    }

    public function isUseDynamic(): bool
    {
        return $this->useDynamic;
    }

    public function getRotation(): float
    {
        return $this->rotation;
    }

    public function getUFixedShift(): float
    {
        return $this->uFixedShift;
    }

    public function getVFixedShift(): float
    {
        return $this->vFixedShift;
    }

    public function getUFixedScale(): float
    {
        return $this->uFixedScale;
    }

    public function getVFixedScale(): float
    {
        return $this->vFixedScale;
    }

    public function getUFixedCenter(): float
    {
        return $this->uFixedCenter;
    }

    public function getVFixedCenter(): float
    {
        return $this->vFixedCenter;
    }

    public function getSpinFreq(): float
    {
        return $this->spinFreq;
    }

    public function getUShiftFreq(): float
    {
        return $this->uShiftFreq;
    }

    public function getVShiftFreq(): float
    {
        return $this->vShiftFreq;
    }

    public function getUScaleFreq(): float
    {
        return $this->uScaleFreq;
    }

    public function getVScaleFreq(): float
    {
        return $this->vScaleFreq;
    }

    public function getUScale(): float
    {
        return $this->uScale;
    }

    public function getVScale(): float
    {
        return $this->vScale;
    }

    public function getUCenter(): float
    {
        return $this->uCenter;
    }

    public function getVCenter(): float
    {
        return $this->vCenter;
    }

    /**
     * @param resource|string $resource
     *
     * @throws InaccessibleResourceException
     */
    public function unpack(&$resource): void
    {
        $this->name = NetworkPacket::unpackStdString($resource);

        $state = NetworkPacket::unpackUInt8($resource);
        $this->useStatic = ($state & (1 << 0)) !== 0;
        $this->useDynamic = ($state & (1 << 1)) !== 0;

        if ($this->useStatic)
        {
            $this->rotation = NetworkPacket::unpackFloat($resource);
            $this->uFixedShift = NetworkPacket::unpackFloat($resource);
            $this->vFixedShift = NetworkPacket::unpackFloat($resource);
            $this->uFixedScale = NetworkPacket::unpackFloat($resource);
            $this->vFixedScale = NetworkPacket::unpackFloat($resource);
            $this->uFixedCenter = NetworkPacket::unpackFloat($resource);
            $this->vFixedCenter = NetworkPacket::unpackFloat($resource);
        }

        if ($this->useDynamic)
        {
            $this->spinFreq = NetworkPacket::unpackFloat($resource);
            $this->uShiftFreq = NetworkPacket::unpackFloat($resource);
            $this->vShiftFreq = NetworkPacket::unpackFloat($resource);
            $this->uScaleFreq = NetworkPacket::unpackFloat($resource);
            $this->vScaleFreq = NetworkPacket::unpackFloat($resource);
            $this->uScale = NetworkPacket::unpackFloat($resource);
            $this->vScale = NetworkPacket::unpackFloat($resource);
            $this->uCenter = NetworkPacket::unpackFloat($resource);
            $this->vCenter = NetworkPacket::unpackFloat($resource);
        }
    }
}
