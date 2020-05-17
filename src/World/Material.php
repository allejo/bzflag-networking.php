<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\World;

use allejo\bzflag\networking\Packets\NetworkPacket;

class Material
{
    /** @var string */
    public $name;

    /** @var bool */
    public $noCulling;

    /** @var bool */
    public $noSorting;

    /** @var bool */
    public $noRadar;

    /** @var bool */
    public $noShadow;

    /** @var bool */
    public $occluder;

    /** @var bool */
    public $groupAlpha;

    /** @var bool */
    public $noLighting;

    /** @var int */
    public $dynamicColor;

    /** @var float[4] */
    public $ambient;

    /** @var float[4] */
    public $diffuse;

    /** @var float[4] */
    public $specular;

    /** @var float[4] */
    public $emission;

    /** @var float */
    public $shininess;

    /** @var float */
    public $alphaThreshold;

    /** @var int */
    public $textureCount;

    /** @var array<int, TextureInfo> */
    public $textures;

    /** @var int */
    public $shaderCount;

    /** @var array<int, ShaderInfo> */
    public $shaders;

    public function __construct()
    {
        $this->textures = [];
        $this->shaders = [];
    }

    public function unpack($resource): void
    {
        $this->name = NetworkPacket::unpackStdString($resource);

        $modeByte = NetworkPacket::unpackUInt8($resource);
        $this->noCulling = ($modeByte & (1 << 0)) !== 0;
        $this->noSorting = ($modeByte & (1 << 1)) !== 0;
        $this->noRadar = ($modeByte & (1 << 2)) !== 0;
        $this->noShadow = ($modeByte & (1 << 3)) !== 0;
        $this->occluder = ($modeByte & (1 << 4)) !== 0;
        $this->groupAlpha = ($modeByte & (1 << 5)) !== 0;
        $this->noLighting = ($modeByte & (1 << 6)) !== 0;

        $inTmp = NetworkPacket::unpackInt32($resource);
        $this->dynamicColor = (int)$inTmp;
        $this->ambient = NetworkPacket::unpack4Float($resource);
        $this->diffuse = NetworkPacket::unpack4Float($resource);
        $this->specular = NetworkPacket::unpack4Float($resource);
        $this->emission = NetworkPacket::unpack4Float($resource);
        $this->shininess = NetworkPacket::unpackFloat($resource);
        $this->alphaThreshold = NetworkPacket::unpackFloat($resource);

        $this->textureCount = NetworkPacket::unpackUInt8($resource);
        for ($i = 0; $i < $this->textureCount; ++$i)
        {
            $textureInfo = new TextureInfo();
            $textureInfo->name = $textureInfo->localName = NetworkPacket::unpackStdString($resource);
            $textureInfo->matrix = (int)NetworkPacket::unpackInt32($resource);
            $textureInfo->combineMode = (int)NetworkPacket::unpackInt32($resource);
            $textureInfo->useAlpha = false;
            $textureInfo->useColor = false;
            $textureInfo->useSphereMap = false;

            $stateByte = NetworkPacket::unpackUInt8($resource);
            if ($stateByte & (1 << 0))
            {
                $textureInfo->useAlpha = true;
            }
            if ($stateByte & (1 << 1))
            {
                $textureInfo->useColor = true;
            }
            if ($stateByte & (1 << 2))
            {
                $textureInfo->useSphereMap = true;
            }

            $this->textures[] = $textureInfo;
        }

        $this->shaderCount = NetworkPacket::unpackUInt8($resource);
        for ($i = 0; $i < $this->shaderCount; ++$i)
        {
            $shaderInfo = new ShaderInfo();
            $shaderInfo->name = NetworkPacket::unpackStdString($this->shaders[$i]->name);
        }
    }
}
