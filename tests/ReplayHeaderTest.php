<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\networking\test;

use allejo\bzflag\networking\Replay;
use PHPUnit\Framework\TestCase;

class ReplayHeaderTest extends TestCase
{
    use ReplayTestTrait;

    /** @var Replay */
    private $replay;

    protected function setUp()
    {
        $this->replay = new Replay(__DIR__ . '/fixtures/replay.rec');
    }

    public function testHeader()
    {
        $header = $this->replay->getHeader();

        self::assertJsonFixtureEqualsSerializable('replayHeader.expected.json', $header);
    }
}
