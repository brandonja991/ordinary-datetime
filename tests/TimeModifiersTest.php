<?php

declare(strict_types=1);

namespace Ordinary\DateTime;

use PHPUnit\Framework\TestCase;

class TimeModifiersTest extends TestCase
{
    use MonthAndWeekProviders;

    public static function assertEndOfSecond(DateTimeImmutable $dateTime): void
    {
        self::assertSame(TimeUnit::Microsecond->max(), $dateTime->microsecond());
    }

    public static function assertEndOfMinute(DateTimeImmutable $dateTime): void
    {
        self::assertSame(TimeUnit::Second->max(), $dateTime->second());
        self::assertEndOfSecond($dateTime);
    }

    public static function assertEndOfHour(DateTimeImmutable $dateTime): void
    {
        self::assertSame(TimeUnit::Minute->max(), $dateTime->minute());
        self::assertEndOfMinute($dateTime);
    }

    public static function assertEndOfDay(DateTimeImmutable $dateTime): void
    {
        self::assertSame(TimeUnit::Hour->max(), $dateTime->hour());
        self::assertEndOfHour($dateTime);
    }

    public static function assertEndOfWeek(DateTimeImmutable $dateTime, DayOfWeek $starOfWeek): void
    {
        $endOfWeek = $starOfWeek->yesterday();

        self::assertSame($endOfWeek, $dateTime->dayOfWeekEnum());
        self::assertEndOfDay($dateTime);
    }

    public static function assertEndOfMonth(DateTimeImmutable $dateTime): void
    {
        self::assertSame($dateTime->monthEnum()->days($dateTime->leapYear()), $dateTime->day());
        self::assertEndOfDay($dateTime);
    }

    public static function assertEndOfQuarter(DateTimeImmutable $dateTime): void
    {
        self::assertSame($dateTime->monthEnum()->endOfQuarter(), $dateTime->monthEnum());
        self::assertEndOfMonth($dateTime);
    }

    public static function assertEndOfSemester(DateTimeImmutable $dateTime): void
    {
        self::assertSame($dateTime->monthEnum()->endOfSemester(), $dateTime->monthEnum());
        self::assertEndOfMonth($dateTime);
    }

    public static function assertEndOfYear(DateTimeImmutable $dateTime): void
    {
        self::assertSame(12, $dateTime->month());
        self::assertEndOfMonth($dateTime);
    }

    public static function assertStartOfSecond(DateTimeImmutable $dateTime): void
    {
        self::assertSame(0, $dateTime->microsecond());
    }

    public static function assertStartOfMinute(DateTimeImmutable $dateTime): void
    {
        self::assertSame(0, $dateTime->second());
        self::assertStartOfSecond($dateTime);
    }

    public static function assertStartOfHour(DateTimeImmutable $dateTime): void
    {
        self::assertSame(0, $dateTime->minute());
        self::assertStartOfMinute($dateTime);
    }

    public static function assertStartOfDay(DateTimeImmutable $dateTime): void
    {
        self::assertSame(0, $dateTime->hour());
        self::assertStartOfHour($dateTime);
    }

    public static function assertStartOfWeek(DateTimeImmutable $dateTime, DayOfWeek $starOfWeek): void
    {
        self::assertSame($starOfWeek, $dateTime->dayOfWeekEnum());
        self::assertStartOfDay($dateTime);
    }

    public static function assertStartOfMonth(DateTimeImmutable $dateTime): void
    {
        self::assertSame(1, $dateTime->day());
        self::assertStartOfDay($dateTime);
    }

    public static function assertStartOfQuarter(DateTimeImmutable $dateTime): void
    {
        self::assertSame($dateTime->monthEnum()->startOfQuarter(), $dateTime->monthEnum());
        self::assertStartOfMonth($dateTime);
    }

    public static function assertStartOfSemester(DateTimeImmutable $dateTime): void
    {
        self::assertSame($dateTime->monthEnum()->startOfSemester(), $dateTime->monthEnum());
        self::assertStartOfMonth($dateTime);
    }

    public static function assertStartOfYear(DateTimeImmutable $dateTime): void
    {
        self::assertSame(1, $dateTime->month());
        self::assertStartOfMonth($dateTime);
    }

    public function testEndOfSecond(): void
    {
        self::assertEndOfSecond((new DateTimeImmutable())->endOfSecond());
    }

    public function testEndOfMinute(): void
    {
        self::assertEndOfMinute((new DateTimeImmutable())->endOfMinute());
    }

    public function testEndOfHour(): void
    {
        self::assertEndOfHour((new DateTimeImmutable())->endOfHour());
    }

    public function testEndOfDay(): void
    {
        self::assertEndOfDay((new DateTimeImmutable())->endOfDay());
    }

    /** @dataProvider weekProvider */
    public function testEndOfWeek(DateTimeImmutable $dateTime, DayOfWeek $startOfWeek): void
    {
        $test = $dateTime->endOfWeek($startOfWeek);

        self::assertEndOfWeek($test, $startOfWeek);


        $endOfWeek = $startOfWeek->yesterday();
        $daysUntil = $dateTime->dayOfWeekEnum()->daysUntil($endOfWeek);

        self::assertEquals($dateTime->assertModify('+' . $daysUntil . ' days')->endOfDay(), $test);
    }

    /** @dataProvider monthProvider */
    public function testEndOfMonth(Month $month): void
    {
        self::assertEndOfMonth((new DateTimeImmutable())->overrideDate(month: $month->value)->endOfMonth());
    }

    /** @dataProvider monthProvider */
    public function testEndOfQuarter(Month $month): void
    {
        $dateTime = (new DateTimeImmutable())->overrideDate(month: $month->value)->endOfQuarter();
        self::assertEndOfQuarter($dateTime);
    }

    /** @dataProvider monthProvider */
    public function testEndOfSemester(Month $month): void
    {
        $dateTime = (new DateTimeImmutable())->overrideDate(month: $month->value)->endOfSemester();
        self::assertEndOfSemester($dateTime);
    }

    public function testEndOfYear(): void
    {
        self::assertEndOfYear((new DateTimeImmutable())->endOfYear());
    }

    public function testStartOfSecond(): void
    {
        self::assertStartOfSecond((new DateTimeImmutable())->startOfSecond());
    }

    public function testStartOfMinute(): void
    {
        self::assertStartOfMinute((new DateTimeImmutable())->startOfMinute());
    }

    public function testStartOfHour(): void
    {
        self::assertStartOfHour((new DateTimeImmutable())->startOfHour());
    }

    public function testStartOfDay(): void
    {
        self::assertStartOfDay((new DateTimeImmutable())->startOfDay());
    }

    /** @dataProvider weekProvider */
    public function testStartOfWeek(DateTimeImmutable $dateTime, DayOfWeek $startOfWeek): void
    {
        $test = $dateTime->startOfWeek($startOfWeek);

        self::assertStartOfWeek($test, $startOfWeek);

        $daysSince = $dateTime->dayOfWeekEnum()->daysSince($startOfWeek);

        self::assertEquals($dateTime->assertModify('-' . $daysSince . ' days'), $test);
    }

    /** @dataProvider monthProvider */
    public function testStartOfMonth(Month $month): void
    {
        self::assertStartOfMonth((new DateTimeImmutable())->overrideDate(month: $month->value)->startOfMonth());
    }

    /** @dataProvider monthProvider */
    public function testStartOfQuarter(Month $month): void
    {
        $dateTime = (new DateTimeImmutable())->overrideDate(month: $month->value)->startOfQuarter();
        self::assertStartOfQuarter($dateTime);
    }

    /** @dataProvider monthProvider */
    public function testStartOfSemester(Month $month): void
    {
        $dateTime = (new DateTimeImmutable())->overrideDate(month: $month->value)->startOfSemester();
        self::assertStartOfSemester($dateTime);
    }

    public function testStartOfYear(): void
    {
        self::assertStartOfYear((new DateTimeImmutable())->startOfYear());
    }
}
