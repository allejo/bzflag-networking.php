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
 * @covers \allejo\bzflag\replays\ReplayHeader
 */
class ReplayPacketsTest extends TestCase
{
    use ReplayTestTrait;

    public function testPacketIndex0()
    {
        $packet = \__::first($this->replay->getPacketsIterable());

        self::assertJsonFixtureEqualsSerializable('replayPackets[0].expected.json', $packet);
    }
}
