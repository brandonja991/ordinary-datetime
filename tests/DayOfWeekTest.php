<?php

declare(strict_types=1);

namespace Ordinary\DateTime;

use Generator;
use PHPUnit\Framework\TestCase;

class DayOfWeekTest extends TestCase
{
    public static function dayOfWeekProvider(): Generator
    {
        foreach (DayOfWeek::cases() as $dayOfWeek) {
            yield match ($dayOfWeek) {
                DayOfWeek::Sunday => [
                    $dayOfWeek,
                    'Sunday',
                    'Sun',
                    7,
                    DayOfWeek::Monday,
                    DayOfWeek::Saturday,
                ],
                DayOfWeek::Monday => [
                    $dayOfWeek,
                    'Monday',
                    'Mon',
                    1,
                    DayOfWeek::Tuesday,
                    DayOfWeek::Sunday,
                ],
                DayOfWeek::Tuesday => [
                    $dayOfWeek,
                    'Tuesday',
                    'Tue',
                    2,
                    DayOfWeek::Wednesday,
                    DayOfWeek::Monday,
                ],
                DayOfWeek::Wednesday => [
                    $dayOfWeek,
                    'Wednesday',
                    'Wed',
                    3,
                    DayOfWeek::Thursday,
                    DayOfWeek::Tuesday,
                ],
                DayOfWeek::Thursday => [
                    $dayOfWeek,
                    'Thursday',
                    'Thu',
                    4,
                    DayOfWeek::Friday,
                    DayOfWeek::Wednesday,
                ],
                DayOfWeek::Friday => [
                    $dayOfWeek,
                    'Friday',
                    'Fri',
                    5,
                    DayOfWeek::Saturday,
                    DayOfWeek::Thursday,
                ],
                DayOfWeek::Saturday => [
                    $dayOfWeek,
                    'Saturday',
                    'Sat',
                    6,
                    DayOfWeek::Sunday,
                    DayOfWeek::Friday,
                ],
            };
        }
    }

    public static function dayDiffProvider(): Generator
    {
        foreach (DayOfWeek::cases() as $a) {
            foreach (DayOfWeek::cases() as $b) {
                yield array_merge([$a, $b], match ([$a, $b]) {
                    [DayOfWeek::Sunday, DayOfWeek::Sunday],
                    [DayOfWeek::Monday, DayOfWeek::Monday],
                    [DayOfWeek::Tuesday, DayOfWeek::Tuesday],
                    [DayOfWeek::Wednesday, DayOfWeek::Wednesday],
                    [DayOfWeek::Thursday, DayOfWeek::Thursday],
                    [DayOfWeek::Friday, DayOfWeek::Friday],
                    [DayOfWeek::Saturday, DayOfWeek::Saturday], => [0, 0],

                    [DayOfWeek::Sunday, DayOfWeek::Monday],
                    [DayOfWeek::Monday, DayOfWeek::Tuesday],
                    [DayOfWeek::Tuesday, DayOfWeek::Wednesday],
                    [DayOfWeek::Wednesday, DayOfWeek::Thursday],
                    [DayOfWeek::Thursday, DayOfWeek::Friday],
                    [DayOfWeek::Friday, DayOfWeek::Saturday],
                    [DayOfWeek::Saturday, DayOfWeek::Sunday] => [6, 1],

                    [DayOfWeek::Sunday, DayOfWeek::Tuesday],
                    [DayOfWeek::Monday, DayOfWeek::Wednesday],
                    [DayOfWeek::Tuesday, DayOfWeek::Thursday],
                    [DayOfWeek::Wednesday, DayOfWeek::Friday],
                    [DayOfWeek::Thursday, DayOfWeek::Saturday],
                    [DayOfWeek::Friday, DayOfWeek::Sunday],
                    [DayOfWeek::Saturday, DayOfWeek::Monday] => [5, 2],

                    [DayOfWeek::Sunday, DayOfWeek::Wednesday],
                    [DayOfWeek::Monday, DayOfWeek::Thursday],
                    [DayOfWeek::Tuesday, DayOfWeek::Friday],
                    [DayOfWeek::Wednesday, DayOfWeek::Saturday],
                    [DayOfWeek::Thursday, DayOfWeek::Sunday],
                    [DayOfWeek::Friday, DayOfWeek::Monday],
                    [DayOfWeek::Saturday, DayOfWeek::Tuesday] => [4, 3],

                    [DayOfWeek::Sunday, DayOfWeek::Thursday],
                    [DayOfWeek::Monday, DayOfWeek::Friday],
                    [DayOfWeek::Tuesday, DayOfWeek::Saturday],
                    [DayOfWeek::Wednesday, DayOfWeek::Sunday],
                    [DayOfWeek::Thursday, DayOfWeek::Monday],
                    [DayOfWeek::Friday, DayOfWeek::Tuesday],
                    [DayOfWeek::Saturday, DayOfWeek::Wednesday] => [3, 4],

                    [DayOfWeek::Sunday, DayOfWeek::Friday],
                    [DayOfWeek::Monday, DayOfWeek::Saturday],
                    [DayOfWeek::Tuesday, DayOfWeek::Sunday],
                    [DayOfWeek::Wednesday, DayOfWeek::Monday],
                    [DayOfWeek::Thursday, DayOfWeek::Tuesday],
                    [DayOfWeek::Friday, DayOfWeek::Wednesday],
                    [DayOfWeek::Saturday, DayOfWeek::Thursday] => [2, 5],

                    [DayOfWeek::Sunday, DayOfWeek::Saturday],
                    [DayOfWeek::Monday, DayOfWeek::Sunday],
                    [DayOfWeek::Tuesday, DayOfWeek::Monday],
                    [DayOfWeek::Wednesday, DayOfWeek::Tuesday],
                    [DayOfWeek::Thursday, DayOfWeek::Wednesday],
                    [DayOfWeek::Friday, DayOfWeek::Thursday],
                    [DayOfWeek::Saturday, DayOfWeek::Friday] => [1, 6],
                });
            }
        }
    }

    /** @dataProvider dayOfWeekProvider */
    public function testDayOfWeek(
        DayOfWeek $dayOfWeek,
        string $fullName,
        string $abbr,
        int $iso8601,
        DayOfWeek $tomorrow,
        DayOfWeek $yesterday,
    ): void {
        self::assertSame($fullName, $dayOfWeek->fullName());
        self::assertSame($abbr, $dayOfWeek->abbr());
        self::assertSame($iso8601, $dayOfWeek->iso8601DayNumber());
        self::assertSame($tomorrow, $dayOfWeek->tomorrow());
        self::assertSame($yesterday, $dayOfWeek->yesterday());

        self::assertSame($dayOfWeek, DayOfWeek::fromName($fullName));
        self::assertSame($dayOfWeek, DayOfWeek::fromName($abbr));
    }

    public function testFromInvalidName(): void
    {
        self::assertNull(DayOfWeek::tryFromName('foobarbaz'));

        self::expectException(UnexpectedValueException::class);
        DayOfWeek::fromName('foobarbaz');
    }

    /** @dataProvider dayDiffProvider */
    public function testDayDiff(DayOfWeek $a, DayOfWeek $b, int $daysSince, int $daysUntil): void
    {
        self::assertSame($daysSince, $a->daysSince($b));
        self::assertSame($daysUntil, $a->daysUntil($b));
    }
}
