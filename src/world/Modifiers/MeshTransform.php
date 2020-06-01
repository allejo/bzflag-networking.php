<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\world\Modifiers;

use allejo\bzflag\networking\InaccessibleResourceException;
use allejo\bzflag\networking\Packets\NetworkPacket;

class MeshTransform
{
    /** @var string */
    private $name;

    /** @var array<int, TransformData> */
    private $transforms;

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array<int, TransformData>
     */
    public function getTransforms(): array
    {
        return $this->transforms;
    }

    /**
     * @param resource|string $resource
     *
     * @throws InaccessibleResourceException
     */
    public function unpack(&$resource): void
    {
        $count = NetworkPacket::unpackUInt32($resource);

        for ($i = 0; $i < $count; ++$i)
        {
            $transform = new TransformData();
            $transform->type = NetworkPacket::unpackUInt8($resource);

            if ($transform->type === TransformType::INDEX_TRANSFORM)
            {
                $transform->index = (int)NetworkPacket::unpackInt32($resource);
                $transform->data = [0.0, 0.0, 0.0, 0.0];
            }
            else
            {
                $transform->index = -1;

                $vectorData = NetworkPacket::unpackVector($resource);
                $transform->data = [
                    $vectorData[0],
                    $vectorData[1],
                    $vectorData[2],
                    0.0,
                ];

                if ($transform->type === TransformType::SPIN_TRANSFORM)
                {
                    $transform->data[3] = NetworkPacket::unpackFloat($resource);
                }
            }

            $this->transforms[] = $transform;
        }
    }
}
