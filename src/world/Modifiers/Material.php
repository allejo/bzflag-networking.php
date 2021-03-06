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
class Material implements \JsonSerializable
{
    use JsonSerializePublicGetters;

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

    /**
     * @since future
     */
    public function __construct()
    {
        $this->textures = [];
        $this->shaders = [];
    }

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
    public function isNoCulling(): bool
    {
        return $this->noCulling;
    }

    /**
     * @since future
     */
    public function isNoSorting(): bool
    {
        return $this->noSorting;
    }

    /**
     * @since future
     */
    public function isNoRadar(): bool
    {
        return $this->noRadar;
    }

    /**
     * @since future
     */
    public function isNoShadow(): bool
    {
        return $this->noShadow;
    }

    /**
     * @since future
     */
    public function isOccluder(): bool
    {
        return $this->occluder;
    }

    /**
     * @since future
     */
    public function isGroupAlpha(): bool
    {
        return $this->groupAlpha;
    }

    /**
     * @since future
     */
    public function isNoLighting(): bool
    {
        return $this->noLighting;
    }

    /**
     * @since future
     */
    public function getDynamicColor(): int
    {
        return $this->dynamicColor;
    }

    /**
     * @since future
     *
     * @return array{float, float, float, float}
     */
    public function getAmbient(): array
    {
        return $this->ambient;
    }

    /**
     * @since future
     *
     * @return array{float, float, float, float}
     */
    public function getDiffuse(): array
    {
        return $this->diffuse;
    }

    /**
     * @since future
     *
     * @return array{float, float, float, float}
     */
    public function getSpecular(): array
    {
        return $this->specular;
    }

    /**
     * @since future
     *
     * @return array{float, float, float, float}
     */
    public function getEmission(): array
    {
        return $this->emission;
    }

    /**
     * @since future
     */
    public function getShininess(): float
    {
        return $this->shininess;
    }

    /**
     * @since future
     */
    public function getAlphaThreshold(): float
    {
        return $this->alphaThreshold;
    }

    /**
     * @since future
     */
    public function getTextureCount(): int
    {
        return $this->textureCount;
    }

    /**
     * @since future
     *
     * @return array<int, TextureInfo>
     */
    public function getTextures(): array
    {
        return $this->textures;
    }

    /**
     * @since future
     */
    public function getShaderCount(): int
    {
        return $this->shaderCount;
    }

    /**
     * @since future
     *
     * @return array<int, ShaderInfo>
     */
    public function getShaders(): array
    {
        return $this->shaders;
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

        $modeByte = NetworkPacket::unpackUInt8($resource);
        $this->noCulling = ($modeByte & (1 << 0)) !== 0;
        $this->noSorting = ($modeByte & (1 << 1)) !== 0;
        $this->noRadar = ($modeByte & (1 << 2)) !== 0;
        $this->noShadow = ($modeByte & (1 << 3)) !== 0;
        $this->occluder = ($modeByte & (1 << 4)) !== 0;
        $this->groupAlpha = ($modeByte & (1 << 5)) !== 0;
        $this->noLighting = ($modeByte & (1 << 6)) !== 0;

        $inTmp = NetworkPacket::unpackInt32($resource);
        $this->dynamicColor = $inTmp;
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
            $textureInfo->matrix = NetworkPacket::unpackInt32($resource);
            $textureInfo->combineMode = NetworkPacket::unpackInt32($resource);
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
