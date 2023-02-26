<?php

declare(strict_types=1);

namespace Ordinary\DateTime;

use Generator;

trait MonthAndWeekProviders
{
    public static function monthProvider(): Generator
    {
        foreach (Month::cases() as $month) {
            yield [$month];
        }
    }

    public static function weekProvider(): Generator
    {
        $datesToTest = [
            '2023-02-25',
            '2020-02-29',
            '2019-01-01',
            '2017-12-31',
        ];

        foreach ($datesToTest as $date) {
            // test each date with each day of week as start day
            $dateObject = new DateTimeImmutable($date);

            foreach (DayOfWeek::cases() as $dayOfWeek) {
                yield [$dateObject, $dayOfWeek];
            }
        }
    }
}
