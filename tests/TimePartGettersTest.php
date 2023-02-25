<?php

declare(strict_types=1);

namespace Ordinary\DateTime;

use Generator;
use PHPUnit\Framework\TestCase;

class TimePartGettersTest extends TestCase
{
    public static function dateProvider(): Generator
    {
        yield [2023, Month::February, 25, DayOfWeek::Saturday];
    }

    public static function timeProvider(): Generator
    {
        yield [23, 59, 59, 999_999];
        yield [0, 0, 0, 0];
    }

    public static function leapYearProvider(): Generator
    {
        yield [2023, false]; // non leap year
        yield [2020, true]; // normal leap year
        yield [2000, true]; // centurial leap year
        yield [1900, false]; // centurial non leap year
    }

    /** @dataProvider dateProvider */
    public function testDateGetters(int $year, Month $month, int $day, DayOfWeek $dayOfWeek): void
    {
        $dateTime = new DateTimeImmutable("$year-{$month->value}-$day");
        self::assertSame($year, $dateTime->year());

        self::assertSame($month, $dateTime->monthEnum());
        self::assertSame($month->value, $dateTime->month());

        self::assertSame($day, $dateTime->day());
        self::assertSame($dayOfWeek, $dateTime->dayOfWeekEnum());
        self::assertSame($dayOfWeek->value, $dateTime->dayOfWeek());
    }

    /** @dataProvider timeProvider */
    public function testTimeGetters(int $hour, int $minute, int $second, int $microsecond): void
    {
        $dateTime = new DateTimeImmutable("today $hour:$minute:$second.$microsecond");

        self::assertSame($hour, $dateTime->hour());
        self::assertSame($minute, $dateTime->minute());
        self::assertSame($second, $dateTime->second());
        self::assertSame($microsecond, $dateTime->microsecond());
    }

    /** @dataProvider leapYearProvider */
    public function testLeapYear(int $year, bool $isLeapYear): void
    {
        $dateTime = new DateTimeImmutable("$year-01-01");

        self::assertSame($year, $dateTime->year());
        self::assertSame($isLeapYear, $dateTime->leapYear());
    }
}
