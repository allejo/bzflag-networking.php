<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\World\Modifiers;

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

    public function getTransforms(): array
    {
        return $this->transforms;
    }

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
                $transform->data = [0, 0, 0, 0];
            }
            else
            {
                $transform->index = -1;
                $transform->data = NetworkPacket::unpackVector($resource);

                if ($transform->type === TransformType::SPIN_TRANSFORM)
                {
                    $transform->data[3] = NetworkPacket::unpackFloat($resource);
                }
                else
                {
                    $transform->data[3] = 0;
                }
            }

            $this->transforms[] = $transform;
        }
    }
}
