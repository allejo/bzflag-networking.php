<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\world\Modifiers;

use allejo\bzflag\networking\Packets\NetworkPacket;

class Material
{
    /** @var string */
    private $name;

    /** @var bool */
    private $noCulling;

    /** @var bool */
    private $noSorting;

    /** @var bool */
    private $noRadar;

    /** @var bool */
    private $noShadow;

    /** @var bool */
    private $occluder;

    /** @var bool */
    private $groupAlpha;

    /** @var bool */
    private $noLighting;

    /** @var int */
    private $dynamicColor;

    /** @var array{float, float, float, float} */
    private $ambient;

    /** @var array{float, float, float, float} */
    private $diffuse;

    /** @var array{float, float, float, float} */
    private $specular;

    /** @var array{float, float, float, float} */
    private $emission;

    /** @var float */
    private $shininess;

    /** @var float */
    private $alphaThreshold;

    /** @var int */
    private $textureCount;

    /** @var array<int, TextureInfo> */
    private $textures;

    /** @var int */
    private $shaderCount;

    /** @var array<int, ShaderInfo> */
    private $shaders;

    public function __construct()
    {
        $this->textures = [];
        $this->shaders = [];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isNoCulling(): bool
    {
        return $this->noCulling;
    }

    public function isNoSorting(): bool
    {
        return $this->noSorting;
    }

    public function isNoRadar(): bool
    {
        return $this->noRadar;
    }

    public function isNoShadow(): bool
    {
        return $this->noShadow;
    }

    public function isOccluder(): bool
    {
        return $this->occluder;
    }

    public function isGroupAlpha(): bool
    {
        return $this->groupAlpha;
    }

    public function isNoLighting(): bool
    {
        return $this->noLighting;
    }

    public function getDynamicColor(): int
    {
        return $this->dynamicColor;
    }

    /**
     * @return array{float, float, float, float}
     */
    public function getAmbient(): array
    {
        return $this->ambient;
    }

    /**
     * @return array{float, float, float, float}
     */
    public function getDiffuse(): array
    {
        return $this->diffuse;
    }

    /**
     * @return array{float, float, float, float}
     */
    public function getSpecular(): array
    {
        return $this->specular;
    }

    /**
     * @return array{float, float, float, float}
     */
    public function getEmission(): array
    {
        return $this->emission;
    }

    public function getShininess(): float
    {
        return $this->shininess;
    }

    public function getAlphaThreshold(): float
    {
        return $this->alphaThreshold;
    }

    public function getTextureCount(): int
    {
        return $this->textureCount;
    }

    /**
     * @return array<int, TextureInfo>
     */
    public function getTextures(): array
    {
        return $this->textures;
    }

    public function getShaderCount(): int
    {
        return $this->shaderCount;
    }

    /**
     * @return array<int, ShaderInfo>
     */
    public function getShaders(): array
    {
        return $this->shaders;
    }

    /**
     * @param resource|string $resource
     */
    public function unpack(&$resource): void
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