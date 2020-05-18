<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\World\Object;

abstract class Obstacle
{
    const DRIVE_THRU = (1 << 0);
    const SHOOT_THRU = (1 << 1);
    const FLIP_Z = (1 << 2);
    const RICOCHET = (1 << 3);

    /** @var array{float, float, float} */
    protected $pos;

    /** @var array{float, float, float} */
    protected $size;

    /** @var float */
    protected $angle;

    /** @var bool */
    protected $driveThrough;

    /** @var bool */
    protected $shootThrough;

    /** @var bool */
    protected $ricochet;

    /** @var bool */
    protected $zFlip;

    /**
     * @return array{float, float, float}
     */
    public function getPosition(): array
    {
        return $this->pos;
    }

    /**
     * @return array{float, float, float}
     */
    public function getSize(): array
    {
        return $this->size;
    }

    public function getRotation(): float
    {
        return $this->angle;
    }

    public function getWidth(): float
    {
        return $this->size[0];
    }

    public function getBreadth(): float
    {
        return $this->size[1];
    }

    public function getHeight(): float
    {
        return $this->size[2];
    }

    public function getZFlip(): bool
    {
        return $this->zFlip;
    }

    public function isDriveThrough(): bool
    {
        return $this->driveThrough;
    }

    public function isShootThrough(): bool
    {
        return $this->shootThrough;
    }

    public function isPassable(): bool
    {
        return $this->driveThrough && $this->shootThrough;
    }

    public function canRicochet(): bool
    {
        return $this->ricochet;
    }

    public function isValid(): bool
    {
        return true;
    }

    abstract public function unpack(&$resource): void;
}