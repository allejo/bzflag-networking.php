<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\world\Object;

use allejo\bzflag\generic\JsonSerializePublicGetters;
use allejo\bzflag\networking\InaccessibleResourceException;
use allejo\bzflag\networking\Packets\NetworkPacket;
use allejo\bzflag\world\NamedObstacleNotFoundException;
use allejo\bzflag\world\WorldDatabase;

class TeleporterLink implements IWorldDatabaseAware, \JsonSerializable
{
    use JsonSerializePublicGetters;

    /** @var string */
    private $src;

    /** @var string */
    private $dst;

    /** @var WorldDatabase */
    private $worldDatabase;

    public function __construct(WorldDatabase $worldDatabase)
    {
        $this->worldDatabase = $worldDatabase;
    }

    public function getWorldDatabase(): WorldDatabase
    {
        return $this->worldDatabase;
    }

    public function getSource(): string
    {
        return $this->src;
    }

    /**
     * @throws NamedObstacleNotFoundException
     *
     * @return array{Teleporter, TeleporterLinkLocation::*}
     */
    public function getSourceTeleporter(): array
    {
        return $this->getTeleporterLinkTuple($this->src);
    }

    public function getDestination(): string
    {
        return $this->dst;
    }

    /**
     * @throws NamedObstacleNotFoundException
     *
     * @return array{Teleporter, TeleporterLinkLocation::*}
     */
    public function getDestinationTeleporter(): array
    {
        return $this->getTeleporterLinkTuple($this->dst);
    }

    /**
     * @param resource|string $resource
     *
     * @throws InaccessibleResourceException
     */
    public function unpack(&$resource): void
    {
        $this->src = NetworkPacket::unpackStdString($resource);
        $this->dst = NetworkPacket::unpackStdString($resource);
    }

    /**
     * @return string[]
     */
    protected function getJsonEncodeBlacklist(): array
    {
        return [
            'worldDatabase',
        ];
    }

    /**
     * @throws NamedObstacleNotFoundException
     *
     * @return array{Teleporter, TeleporterLinkLocation::*}
     */
    private function getTeleporterLinkTuple(string $link): array
    {
        [$name, $direction] = explode(':', $link);

        /** @var Teleporter $tele */
        $tele = $this->worldDatabase
            ->getObstacleManager()
            ->getWorld()
            ->getNamedObstacle(ObstacleType::TELE_TYPE, $name)
        ;

        /** @var TeleporterLinkLocation::* $dir */
        $dir = TeleporterLinkLocation::fromBZW($direction);

        return [$tele, $dir];
    }
}