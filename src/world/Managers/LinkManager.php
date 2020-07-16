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
use allejo\bzflag\world\Object\TeleporterLink;
use allejo\bzflag\world\WorldDatabase;

class LinkManager extends BaseManager
{
    /** @var TeleporterLink[] */
    private $links;

    public function __construct(WorldDatabase $worldDatabase)
    {
        parent::__construct($worldDatabase);

        $this->links = [];
    }

    /**
     * @return TeleporterLink[]
     */
    public function getLinks(): array
    {
        return $this->links;
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
            $link = new TeleporterLink($this->worldDatabase);
            $link->unpack($resource);

            $this->links[] = $link;
        }
    }
}
