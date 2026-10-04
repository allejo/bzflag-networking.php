<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\test\networking;

use allejo\bzflag\networking\Packets\MsgPlayerUpdate;
use allejo\bzflag\networking\Packets\NetworkMessage;
use allejo\bzflag\networking\Packets\NetworkPacket;
use allejo\bzflag\replays\Replay;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \allejo\bzflag\networking\Packets\NetworkPacket
 * @covers \allejo\bzflag\replays\Replay
 * @covers \allejo\bzflag\replays\ReplayHeader
 */
class NetworkPacketPackTest extends TestCase
{
    private const REPLAY = __DIR__ . '/../replays/fixtures/replay.rec';

    public function testPackPrimitivesRoundTrip()
    {
        $buffer = NetworkPacket::packInt8(-5)
            . NetworkPacket::packInt16(-1234)
            . NetworkPacket::packInt32(-123456)
            . NetworkPacket::packUInt32(4000000000)
            . NetworkPacket::packFloat(1.5)
            . NetworkPacket::packString('allejo', 32)
            . NetworkPacket::packStdString('hello')
            . NetworkPacket::packIpAddress('192.168.1.10');

        self::assertSame(-5, NetworkPacket::unpackInt8($buffer));
        self::assertSame(-1234, NetworkPacket::unpackInt16($buffer));
        self::assertSame(-123456, NetworkPacket::unpackInt32($buffer));
        self::assertSame(4000000000, NetworkPacket::unpackUInt32($buffer));
        self::assertSame(1.5, NetworkPacket::unpackFloat($buffer));
        self::assertSame('allejo', NetworkPacket::unpackString($buffer, 32));
        self::assertSame('hello', NetworkPacket::unpackStdString($buffer));
        self::assertSame('192.168.1.10', NetworkPacket::unpackIpAddress($buffer));
        self::assertSame('', $buffer);
    }

    public function testGamePacketsRepackToOriginalBytes()
    {
        $replay = new Replay(self::REPLAY);
        $contents = (string)file_get_contents(self::REPLAY);
        $count = 0;

        foreach ($replay->getPacketsIterable() as $packet)
        {
            $raw = $packet->getRawPacket();

            self::assertNotFalse(strpos($contents, $raw->pack()));
            self::assertSame((string)$raw->getData(), $packet->pack(), $packet->getPacketType());
            ++$count;
        }

        self::assertGreaterThan(0, $count);
    }

    public function testGetErrorsIsEmptyWithoutErrors()
    {
        self::assertSame([], (new Replay(self::REPLAY))->getErrors());
    }

    public function testRewriteWithoutFilteringIsIdentical()
    {
        $file = tempnam(sys_get_temp_dir(), 'bzrec');

        (new Replay(self::REPLAY))->write($file, function () {
            return true;
        });

        self::assertSame(file_get_contents(self::REPLAY), file_get_contents($file));
        unlink($file);
    }

    public function testRewriteWithFilteredPackets()
    {
        $file = tempnam(sys_get_temp_dir(), 'bzrec');
        $original = new Replay(self::REPLAY);

        $original->write($file, function (NetworkPacket $packet) {
            return $packet->getCode() !== NetworkMessage::PLAYER_UPDATE_SMALL;
        });

        $filtered = new Replay($file);
        $before = iterator_to_array($original->getPacketsIterable(), false);
        $after = iterator_to_array($filtered->getPacketsIterable(), false);

        self::assertSame($original->getHeader()->pack(), $filtered->getHeader()->pack());
        self::assertLessThan(count($before), count($after));
        self::assertCount(0, array_filter($after, function ($p) {
            return $p instanceof MsgPlayerUpdate;
        }));
        self::assertSame(count($before), count($after) + count(array_filter($before, function ($p) {
            return $p instanceof MsgPlayerUpdate;
        })));

        // File positions must chain together and end at EOF
        $resource = fopen($file, 'rb');
        $filePos = strlen($filtered->getHeader()->pack());
        $prevFilePos = 0;
        fseek($resource, $filePos);

        while (ftell($resource) < filesize($file))
        {
            $packet = new NetworkPacket($resource);

            self::assertSame($prevFilePos, $packet->getPrevFilePos());
            self::assertSame(ftell($resource), $packet->getNextFilePos());

            $prevFilePos = $filePos;
            $filePos = ftell($resource);
        }

        fclose($resource);
        unlink($file);
    }
}
