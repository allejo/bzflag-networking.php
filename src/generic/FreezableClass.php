<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\generic;

/**
 * @internal
 */
trait FreezableClass
{
    /** @var bool */
    private $frozen;

    public function __clone()
    {
        $this->unfreeze();
    }

    public function isFrozen(): bool
    {
        return $this->frozen;
    }

    /**
     * Freeze this obstacle so no further modifications can be made to it.
     */
    public function freeze(): void
    {
        $this->frozen = true;
    }

    /**
     * @throws FrozenObstacleException
     */
    protected function frozenObstacleCheck(): void
    {
        if ($this->frozen)
        {
            throw new FrozenObstacleException('Cannot modify a obstacle that has been frozen.');
        }
    }

    private function unfreeze(): void
    {
        $this->frozen = false;
    }
}
