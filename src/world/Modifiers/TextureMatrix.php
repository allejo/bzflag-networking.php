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

/**
 * @since future
 */
class TextureMatrix implements \JsonSerializable
{
    use JsonSerializePublicGetters;

    /** @var string */
    private $name = '';

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

    /**
     * @since future
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @since future
     */
    public function isUseStatic(): bool
    {
        return $this->useStatic;
    }

    /**
     * @since future
     */
    public function isUseDynamic(): bool
    {
        return $this->useDynamic;
    }

    /**
     * @since future
     */
    public function getRotation(): float
    {
        return $this->rotation;
    }

    /**
     * @since future
     */
    public function getUFixedShift(): float
    {
        return $this->uFixedShift;
    }

    /**
     * @since future
     */
    public function getVFixedShift(): float
    {
        return $this->vFixedShift;
    }

    /**
     * @since future
     */
    public function getUFixedScale(): float
    {
        return $this->uFixedScale;
    }

    /**
     * @since future
     */
    public function getVFixedScale(): float
    {
        return $this->vFixedScale;
    }

    /**
     * @since future
     */
    public function getUFixedCenter(): float
    {
        return $this->uFixedCenter;
    }

    /**
     * @since future
     */
    public function getVFixedCenter(): float
    {
        return $this->vFixedCenter;
    }

    /**
     * @since future
     */
    public function getSpinFreq(): float
    {
        return $this->spinFreq;
    }

    /**
     * @since future
     */
    public function getUShiftFreq(): float
    {
        return $this->uShiftFreq;
    }

    /**
     * @since future
     */
    public function getVShiftFreq(): float
    {
        return $this->vShiftFreq;
    }

    /**
     * @since future
     */
    public function getUScaleFreq(): float
    {
        return $this->uScaleFreq;
    }

    /**
     * @since future
     */
    public function getVScaleFreq(): float
    {
        return $this->vScaleFreq;
    }

    /**
     * @since future
     */
    public function getUScale(): float
    {
        return $this->uScale;
    }

    /**
     * @since future
     */
    public function getVScale(): float
    {
        return $this->vScale;
    }

    /**
     * @since future
     */
    public function getUCenter(): float
    {
        return $this->uCenter;
    }

    /**
     * @since future
     */
    public function getVCenter(): float
    {
        return $this->vCenter;
    }

    /**
     * @since future
     *
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
