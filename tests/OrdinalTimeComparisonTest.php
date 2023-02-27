<?php

declare(strict_types=1);

namespace Ordinary\DateTime;

use Generator;
use LogicException;
use PHPUnit\Framework\TestCase;

class OrdinalTimeComparisonTest extends TestCase
{
    public static function dateTimeProvider(): Generator
    {
        yield [
            '2021-01-01T12:30:30.999998',
            '2021-01-01T12:30:30.999999',
            -1,
        ];

        yield [
            '2021-01-01T12:30:30.999999',
            '2021-01-01T12:30:30.999999',
            0,
        ];
    }

    /** @dataProvider dateTimeProvider */
    public function testCompare(string $dateA, string $dateB, int $expected): void
    {
        $a = new DateTimeImmutable($dateA);
        $b = new DateTimeImmutable($dateB);

        switch ($expected) {
            case -1:
                self::assertTrue($a->isBefore($b));
                self::assertTrue($a->isAtOrBefore($b));
                self::assertTrue($b->isAfter($a));
                self::assertTrue($b->isAtOrAfter($a));

                self::assertFalse($a->isAfter($b));
                self::assertFalse($a->isAtOrAfter($b));
                self::assertFalse($b->isBefore($a));
                self::assertFalse($b->isAtOrBefore($a));

                self::assertFalse($a->isAt($b));
                self::assertFalse($b->isAt($a));

                break;

            case 0:
                self::assertFalse($a->isBefore($b));
                self::assertFalse($b->isBefore($a));
                self::assertFalse($a->isAfter($b));
                self::assertFalse($b->isAfter($a));

                self::assertTrue($a->isAtOrBefore($b));
                self::assertTrue($b->isAtOrBefore($a));
                self::assertTrue($a->isAtOrAfter($b));
                self::assertTrue($b->isAtOrAfter($a));

                self::assertTrue($a->isAt($b));
                self::assertTrue($b->isAt($a));

                break;

            case 1:
                self::assertFalse($a->isBefore($b));
                self::assertFalse($a->isAtOrBefore($b));
                self::assertFalse($b->isAfter($a));
                self::assertFalse($b->isAtOrAfter($a));

                self::assertTrue($a->isAfter($b));
                self::assertTrue($a->isAtOrAfter($b));
                self::assertTrue($b->isBefore($a));
                self::assertTrue($b->isAtOrBefore($a));

                break;

            default:
                throw new LogicException('Unexpected value for $expected');
        }
    }
}
