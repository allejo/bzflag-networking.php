<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\test\replays;

use allejo\bzflag\replays\Replay;

trait ReplayTestTrait
{
    /** @var Replay */
    protected $replay;

    protected function setUp()
    {
        $this->replay = new Replay(__DIR__ . '/fixtures/replay.rec');
    }

    public static function getJsonFixture(string $filename): string
    {
        return file_get_contents(__DIR__ . '/fixtures/' . $filename);
    }

    public static function assertJsonStringEqualsSerializable(string $json, \JsonSerializable $object): void
    {
        $expected = json_decode($json, true);
        $encoded = json_encode($object);

        if (json_last_error() > 0)
        {
            throw new \JsonException(json_last_error_msg());
        }

        $actual = json_decode($encoded, true);

        static::assertEquals($expected, $actual);
    }

    public static function assertJsonFixtureEqualsSerializable(string $filename, \JsonSerializable $object): void
    {
        self::assertJsonStringEqualsSerializable(self::getJsonFixture($filename), $object);
    }
}
