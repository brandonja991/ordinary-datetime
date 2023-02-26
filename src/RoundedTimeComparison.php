<?php

declare(strict_types=1);

namespace Ordinary\DateTime;

use DateTimeInterface;

trait RoundedTimeComparison
{
    use TimeModifiers;

    abstract public static function assertSelf(DateTimeInterface $dateTime): DateTimeImmutable;

    public function isSameSemester(DateTimeInterface $dateTime): bool
    {
        $dateTime = $dateTime instanceof self ? $dateTime : self::assertSelf($dateTime);

        return $dateTime->year() === $this->year()
            && $this->monthEnum()->startOfSemester() === $dateTime->monthEnum()->startOfSemester();
    }

    public function isSameQuarter(DateTimeInterface $dateTime): bool
    {
        $dateTime = $dateTime instanceof self ? $dateTime : self::assertSelf($dateTime);

        return $dateTime->year() === $this->year()
            && $this->monthEnum()->startOfQuarter() === $dateTime->monthEnum()->startOfQuarter();
    }

    public function isSameYear(DateTimeInterface $dateTime): bool
    {
        $dateTime = $dateTime instanceof self ? $dateTime : self::assertSelf($dateTime);

        return $dateTime->year() === $this->year();
    }

    public function isSameMonth(DateTimeInterface $dateTime): bool
    {
        $dateTime = $dateTime instanceof self ? $dateTime : self::assertSelf($dateTime);

        return $this->isSameYear($dateTime)
            && $dateTime->month() === $this->month();
    }

    public function isSameWeek(DateTimeInterface $dateTime, DayOfWeek $startOfWeek = DayOfWeek::Sunday): bool
    {
        $dateTime = $dateTime instanceof self ? $dateTime : self::assertSelf($dateTime);

        return $dateTime->startOfWeek($startOfWeek)->isSameDay($this->startOfWeek($startOfWeek));
    }

    public function isSameDay(DateTimeInterface $dateTime): bool
    {
        $dateTime = $dateTime instanceof self ? $dateTime : self::assertSelf($dateTime);

        return $this->isSameMonth($dateTime)
            && $dateTime->day() === $this->day();
    }

    public function isSameHour(DateTimeInterface $dateTime): bool
    {
        $dateTime = $dateTime instanceof self ? $dateTime : self::assertSelf($dateTime);

        return $this->isSameDay($dateTime)
            && $dateTime->hour() === $this->hour();
    }

    public function isSameMinute(DateTimeInterface $dateTime): bool
    {
        $dateTime = $dateTime instanceof self ? $dateTime : self::assertSelf($dateTime);

        return $this->isSameHour($dateTime)
            && $dateTime->minute() === $this->minute();
    }

    public function isSameSecond(DateTimeInterface $dateTime): bool
    {
        $dateTime = $dateTime instanceof self ? $dateTime : self::assertSelf($dateTime);

        return $this->isSameMinute($dateTime)
            && $dateTime->second() === $this->second();
    }
}
