<?php

declare(strict_types=1);

namespace Ordinary\DateTime;

use DateTime;
use DateTimeImmutable as PHPDateTimeImmutableAlias;
use Exception;
use Generator;
use PHPUnit\Framework\TestCase;

class DateTimeImmutableTest extends TestCase
{
    public static function overrideTimeProvider(): Generator
    {
        yield [0, 0, 0, 0, null]; // valid lower bounds

        yield [23, 59, 59, 999_999, null]; // valid upper bounds

        yield [0, 0, 0, 1_000_000, UnexpectedValueException::class]; // over bound microsecond
        yield [0, 0, 0, -1, UnexpectedValueException::class]; // under bound microsecond

        yield [0, 0, 60, 0, UnexpectedValueException::class]; // over bound second
        yield [0, 0, -1, 0, UnexpectedValueException::class]; // under bound second

        yield [0, 60, 0, 0, UnexpectedValueException::class]; // over bound minute
        yield [0, -1, 0, 0, UnexpectedValueException::class]; // under bound minute

        yield [24, 0, 0, 0, UnexpectedValueException::class]; // over bound hour
        yield [-1, 0, 0, 0, UnexpectedValueException::class]; // under bound hour
    }

    public static function overrideDateProvider(): Generator
    {
        foreach (Month::cases() as $month) {
            yield [0, $month->value, 1, null]; // valid month lower bounds
            yield [9999, $month->value, $month->days(), null]; // valid month upper bounds
            yield [2020, $month->value, $month->days(true), null]; // valid month upper bounds leap year

            yield [-1, $month->value, 1, UnexpectedValueException::class]; // invalid year lower bound
            yield [10_000, $month->value, 1, UnexpectedValueException::class]; // invalid year upper bound

            yield [0, $month->value, 0, UnexpectedValueException::class]; // invalid day lower bound
            yield [9999, $month->value, $month->days() + 1, UnexpectedValueException::class]; // invalid day upper bound
            yield [
                2020,
                $month->value,
                $month->days(true) + 1,
                UnexpectedValueException::class,
            ]; // invalid day upper bound leap year
        }

        yield [0, 0, 1, UnexpectedValueException::class]; // invalid month lower bound
        yield [0, 13, 1, UnexpectedValueException::class]; // invalid month upper bound
    }

    public static function assertModifyProvider(): Generator
    {
        yield ['1 day ago', null];
        yield ['foobarbaz', UnexpectedValueException::class];
    }

    /**
     * @param ?class-string<Exception> $exception
     * @dataProvider  assertModifyProvider
     */
    public function testAssertModify(string $modifier, ?string $exception): void
    {
        $og = new DateTimeImmutable();

        if ($exception) {
            self::expectException($exception);
        }

        $test = $og->assertModify($modifier);

        self::assertInstanceOf(DateTimeImmutable::class, $test);
    }

    public function testAssertSelf(): void
    {
        $test1 = new DateTimeImmutable();

        self::assertSame($test1, DateTimeImmutable::assertSelf($test1));

        $test2 = new DateTime();
        $result2 = DateTimeImmutable::assertSelf($test2);

        self::assertNotSame($test2, $result2);
        self::assertEquals($test2, $result2);
        self::assertInstanceOf(DateTimeImmutable::class, $result2);

        $test3 = new PHPDateTimeImmutableAlias();
        $result3 = DateTimeImmutable::assertSelf($test3);

        self::assertNotSame($test3, $result3);
        self::assertEquals($test3, $result3);
        self::assertInstanceOf(DateTimeImmutable::class, $result3);
    }

    /**
     * @param ?class-string<Exception> $exception
     * @dataProvider overrideDateProvider
     */
    public function testOverrideDate(?int $year, ?int $month, ?int $day, ?string $exception): void
    {
        $og = new DateTimeImmutable();

        if ($exception) {
            self::expectException($exception);
        }

        $test = $og->overrideDate($year, $month, $day);

        self::assertSame($year ?? $og->year(), $test->year());
        self::assertSame($month ?? $og->month(), $test->month());
        self::assertSame($day ?? $og->day(), $test->day());
    }

    /**
     * @param ?class-string<Exception> $exception
     * @dataProvider overrideTimeProvider
     */
    public function testOverrideTime(
        ?int $hour,
        ?int $minute,
        ?int $second,
        ?int $microsecond,
        ?string $exception,
    ): void {
        $og = new DateTimeImmutable();

        if ($exception) {
            self::expectException($exception);
        }

        $test = $og->overrideTime($hour, $minute, $second, $microsecond);

        self::assertSame($hour ?? $og->hour(), $test->hour());
        self::assertSame($minute ?? $og->minute(), $test->minute());
        self::assertSame($second ?? $og->second(), $test->second());
        self::assertSame($microsecond ?? $og->microsecond(), $test->microsecond());
    }

    public function testEarliest(): void
    {
        $obj = DateTimeImmutable::earliest();
        self::assertSame(0, $obj->year());
        self::assertSame(1, $obj->month());
        self::assertSame(1, $obj->day());
        self::assertSame(0, $obj->hour());
        self::assertSame(0, $obj->minute());
        self::assertSame(0, $obj->second());
        self::assertSame(0, $obj->microsecond());
    }

    public function testLatest(): void
    {
        $obj = DateTimeImmutable::latest();
        self::assertSame(9999, $obj->year());
        self::assertSame(12, $obj->month());
        self::assertSame(31, $obj->day());
        self::assertSame(TimeUnit::Hour->max(), $obj->hour());
        self::assertSame(TimeUnit::Minute->max(), $obj->minute());
        self::assertSame(TimeUnit::Second->max(), $obj->second());
        self::assertSame(TimeUnit::Microsecond->max(), $obj->microsecond());
    }
}
