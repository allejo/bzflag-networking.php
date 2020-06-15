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
}
