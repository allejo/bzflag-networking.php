<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\world\Managers;

use allejo\bzflag\networking\Exceptions\InaccessibleResourceException;
use allejo\bzflag\networking\Packets\NetworkPacket;
use allejo\bzflag\world\Modifiers\MeshTransform;

/**
 * @since future
 */
class TransformManager extends BaseManager
{
    /** @var array<int, MeshTransform> */
    private $meshTransforms = [];

    /**
     * @since future
     *
     * @return array<int, MeshTransform>
     */
    public function getMeshTransforms(): array
    {
        return $this->meshTransforms;
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
        $count = NetworkPacket::unpackUInt32($resource);

        for ($i = 0; $i < $count; ++$i)
        {
            $meshTransform = new MeshTransform();
            $meshTransform->unpack($resource);

            $this->meshTransforms[] = $meshTransform;
        }
    }
}
