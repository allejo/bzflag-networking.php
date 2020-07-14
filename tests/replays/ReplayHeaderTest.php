<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\test\replays;

use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \allejo\bzflag\networking\ReplayHeader
 */
class ReplayHeaderTest extends TestCase
{
    use ReplayTestTrait;

    public function testHeader(): void
    {
        $header = $this->replay->getHeader();

        self::assertJsonFixtureEqualsSerializable('replayHeader.expected.json', $header);
    }

    public function testStaticBZDB(): void
    {
        $worldDB = $this->replay->getHeader()->getWorldDatabase();
        $bzdb = $worldDB->getBZDBManager();

        self::assertEquals('800.0', $bzdb->getBZDBVariable('_worldSize'));
    }

    public function testCalculatedBZDB(): void
    {
        $worldDB = $this->replay->getHeader()->getWorldDatabase();
        $bzdb = $worldDB->getBZDBManager();

        self::assertEquals(400.0, $bzdb->getBZDBVariable('_fogStart'));
    }
}
