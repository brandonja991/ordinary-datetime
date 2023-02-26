<?php

declare(strict_types=1);

namespace Ordinary\DateTime;

use Generator;
use PHPUnit\Framework\TestCase;

class RoundedTimeComparisonTest extends TestCase
{
    use MonthAndWeekProviders;

    public static function yearProvider(): Generator
    {
        yield [0];
        yield [1900];
        yield [2000];
        yield [2023];
        yield [9999];
    }

    public function testIsSameSecond(): void
    {
        $original = new DateTimeImmutable();
        self::assertTrue($original->startOfSecond()->isSameSecond($original->endOfSecond()));

        self::assertFalse(
            $original->startOfSecond()
                ->assertModify('-1 microsecond')
                ->isSameSecond($original->endOfSecond()),
        );

        self::assertFalse(
            $original->startOfSecond()
                ->isSameSecond($original->endOfSecond()->assertModify('+1 microsecond')),
        );
    }

    public function testIsSameMinute(): void
    {
        $original = new DateTimeImmutable();
        self::assertTrue($original->startOfMinute()->isSameMinute($original->endOfMinute()));

        self::assertFalse(
            $original->startOfMinute()
                ->assertModify('-1 microsecond')
                ->isSameMinute($original->endOfMinute()),
        );

        self::assertFalse(
            $original->startOfMinute()
                ->isSameMinute($original->endOfMinute()->assertModify('+1 microsecond')),
        );
    }

    public function testIsSameHour(): void
    {
        $original = new DateTimeImmutable();
        self::assertTrue($original->startOfHour()->isSameHour($original->endOfHour()));

        self::assertFalse(
            $original->startOfHour()
                ->assertModify('-1 microsecond')
                ->isSameHour($original->endOfHour()),
        );

        self::assertFalse(
            $original->startOfHour()
                ->isSameHour($original->endOfHour()->assertModify('+1 microsecond')),
        );
    }

    public function testIsSameDay(): void
    {
        $original = new DateTimeImmutable();
        self::assertTrue($original->startOfDay()->isSameDay($original->endOfDay()));

        self::assertFalse(
            $original->startOfDay()
                ->assertModify('-1 microsecond')
                ->isSameDay($original->endOfDay()),
        );

        self::assertFalse(
            $original->startOfDay()
                ->isSameDay($original->endOfDay()->assertModify('+1 microsecond')),
        );
    }

    /** @dataProvider weekProvider */
    public function testIsSameWeek(DateTimeImmutable $original, DayOfWeek $startOfWeek): void
    {
        self::assertTrue($original->startOfWeek($startOfWeek)->isSameWeek(
            $original->endOfWeek($startOfWeek),
            $startOfWeek,
        ));

        self::assertFalse(
            $original->startOfWeek($startOfWeek)
                ->assertModify('-1 microsecond')
                ->isSameWeek($original->endOfWeek($startOfWeek), $startOfWeek),
        );

        self::assertFalse(
            $original->startOfWeek($startOfWeek)
                ->isSameWeek($original->endOfWeek($startOfWeek)->assertModify('+1 microsecond'), $startOfWeek),
        );
    }

    /** @dataProvider monthProvider */
    public function testIsSameMonth(Month $month): void
    {
        $original = new DateTimeImmutable($month->fullName() . ' 1st');

        self::assertTrue($original->startOfMonth()->isSameMonth($original->endOfMonth()));

        self::assertFalse(
            $original->startOfMonth()
                ->assertModify('-1 microsecond')
                ->isSameMonth($original->endOfMonth()),
        );

        self::assertFalse(
            $original->startOfMonth()
                ->isSameMonth($original->endOfMonth()->assertModify('+1 microsecond')),
        );
    }

    /** @dataProvider monthProvider */
    public function testIsSameQuarter(Month $month): void
    {
        $original = new DateTimeImmutable($month->fullName() . ' 1st');

        self::assertTrue($original->startOfQuarter()->isSameQuarter($original->endOfQuarter()));

        self::assertFalse(
            $original->startOfQuarter()
                ->assertModify('-1 microsecond')
                ->isSameQuarter($original->endOfQuarter()),
        );

        self::assertFalse(
            $original->startOfQuarter()
                ->isSameQuarter($original->endOfQuarter()->assertModify('+1 microsecond')),
        );
    }

    /** @dataProvider monthProvider */
    public function testIsSameSemester(Month $month): void
    {
        $original = new DateTimeImmutable($month->fullName() . ' 1st');

        self::assertTrue($original->startOfSemester()->isSameSemester($original->endOfSemester()));

        self::assertFalse(
            $original->startOfSemester()
                ->assertModify('-1 microsecond')
                ->isSameSemester($original->endOfSemester()),
        );

        self::assertFalse(
            $original->startOfSemester()
                ->isSameSemester($original->endOfSemester()->assertModify('+1 microsecond')),
        );
    }

    /** @dataProvider yearProvider */
    public function testIsSameYear(int $year): void
    {
        $original = new DateTimeImmutable($year . '-01-01');

        self::assertTrue($original->startOfYear()->isSameYear($original->endOfYear()));

        self::assertFalse(
            $original->startOfYear()
                ->assertModify('-1 microsecond')
                ->isSameYear($original->endOfYear()),
        );

        self::assertFalse(
            $original->startOfYear()
                ->isSameYear($original->endOfYear()->assertModify('+1 microsecond')),
        );
    }
}
