<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

namespace allejo\bzflag\test\world\Object;

use allejo\bzflag\world\Object\BoxBuilding;
use allejo\bzflag\world\Object\GroupDefinition;
use allejo\bzflag\world\Object\ObstacleType;
use allejo\bzflag\world\WorldDatabase;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers GroupDefinition
 */
class GroupDefinitionTest extends TestCase
{
    public function testSetObstaclesByTypeFullReplace(): void
    {
        $obstacles = [
            ObstacleType::WALL_TYPE => [],
            ObstacleType::BOX_TYPE => [
                $this->createMock(BoxBuilding::class),
            ],
            ObstacleType::PYR_TYPE => [],
            ObstacleType::BASE_TYPE => [],
            ObstacleType::TELE_TYPE => [],
            ObstacleType::MESH_TYPE => [],
            ObstacleType::ARC_TYPE => [],
            ObstacleType::CONE_TYPE => [],
            ObstacleType::SPHERE_TYPE => [],
            ObstacleType::TETRA_TYPE => [],
        ];

        $worldDB = $this->createMock(WorldDatabase::class);
        $groupDef = new GroupDefinition('', $worldDB);
        $groupDef->setObstaclesByType($obstacles);

        self::assertEquals($groupDef->getObstaclesByType(), $obstacles);
    }

    public function testSetObstaclesByTypePartialReplace()
    {
        $worldDB = $this->createMock(WorldDatabase::class);
        $groupDef = new GroupDefinition('', $worldDB);

        $startingObstacles = $groupDef->getObstaclesByType();

        foreach ($startingObstacles as $startingObstacle)
        {
            self::assertEmpty($startingObstacle);
        }

        $newBoxObstacles = [
            $this->createMock(BoxBuilding::class),
            $this->createMock(BoxBuilding::class),
        ];

        $groupDef->setObstaclesByType($newBoxObstacles, ObstacleType::BOX_TYPE);

        self::assertEquals($newBoxObstacles, $groupDef->getObstaclesByType(ObstacleType::BOX_TYPE));
    }

    public static function dataProvider_testSetObstaclesByTypeExceptions(): array
    {
        return [
            [
                '$obstacles does not match the expected structure',
                [],
                null,
            ],
            [
                'No field for Obstacle Type 9',
                [
                    0 => [],
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => [],
                    6 => [],
                    7 => [],
                    8 => [],
                    14 => [],
                ],
                null,
            ],
            [
                'Invalid Obstacle Type value: 14',
                [],
                14,
            ],
        ];
    }

    /**
     * @dataProvider dataProvider_testSetObstaclesByTypeExceptions
     */
    public function testSetObstaclesByTypeExceptions(?string $exceptionMessage, array $obstacles, ?int $type): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage($exceptionMessage ?? '');

        $worldDB = $this->createMock(WorldDatabase::class);
        $groupDef = new GroupDefinition('', $worldDB);
        $groupDef->setObstaclesByType($obstacles, $type);
    }
}
