<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\World;

use allejo\bzflag\networking\Packets\NetworkPacket;

class TextureMatrix
{
    /** @var string */
    public $name;

    /** @var bool */
    public $useStatic;

    /** @var bool */
    public $useDynamic;

    //
    // Static Properties
    //

    /** @var float */
    public $rotation;

    /** @var float */
    public $uFixedShift;

    /** @var float */
    public $vFixedShift;

    /** @var float */
    public $uFixedScale;

    /** @var float */
    public $vFixedScale;

    /** @var float */
    public $uFixedCenter;

    /** @var float */
    public $vFixedCenter;

    //
    // Dynamic Properties
    //

    /** @var float */
    public $spinFreq;

    /** @var float */
    public $uShiftFreq;

    /** @var float */
    public $vShiftFreq;

    /** @var float */
    public $uScaleFreq;

    /** @var float */
    public $vScaleFreq;

    /** @var float */
    public $uScale;

    /** @var float */
    public $vScale;

    /** @var float */
    public $uCenter;

    /** @var float */
    public $vCenter;

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
