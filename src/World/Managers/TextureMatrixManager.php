<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\World\Managers;

use allejo\bzflag\networking\Packets\NetworkPacket;
use allejo\bzflag\networking\World\Modifiers\TextureMatrix;

class TextureMatrixManager
{
    /** @var array<int, TextureMatrix> */
    private $textures;

    public function __construct()
    {
        $this->textures = [];
    }

    /**
     * @return array<int, TextureMatrix>
     */
    public function getTextures(): array
    {
        return $this->textures;
    }

    /**
     * @param resource|string $resource
     */
    public function unpack(&$resource): void
    {
        $count = NetworkPacket::unpackUInt32($resource);

        for ($i = 0; $i < $count; ++$i)
        {
            $textureMatrix = new TextureMatrix();
            $textureMatrix->unpack($resource);

            $this->textures[] = $textureMatrix;
        }
    }
}
