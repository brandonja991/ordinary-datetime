<?php

declare(strict_types=1);

namespace Ordinary\DateTime;

trait TimeModifiers
{
    use TimePartGetters;

    abstract public function overrideTime(
        ?int $hour = null,
        ?int $minute = null,
        ?int $second = null,
        ?int $microsecond = null,
    ): DateTimeImmutable;

    abstract public function overrideDate(?int $year = null, ?int $month = null, ?int $day = null): DateTimeImmutable;

    abstract public function assertModify(string $modifier): DateTimeImmutable;

    public function startOfSecond(): DateTimeImmutable
    {
        return $this->overrideTime(
            microsecond: 0,
        );
    }

    public function startOfMinute(): DateTimeImmutable
    {
        return $this->overrideTime(
            second: 0,
            microsecond: 0,
        );
    }

    public function startOfHour(): DateTimeImmutable
    {
        return $this->overrideTime(
            minute: 0,
            second: 0,
            microsecond: 0,
        );
    }

    public function startOfDay(): DateTimeImmutable
    {
        return $this->overrideTime(0, 0, 0, 0);
    }

    public function startOfWeek(DayOfWeek $startOfWeek = DayOfWeek::Sunday): DateTimeImmutable
    {
        $new = $this->dayOfWeekEnum() !== $startOfWeek
            ? $this->assertModify('last ' . $startOfWeek->fullName())
            : $this;

        return $new->startOfDay();
    }

    public function startOfMonth(): DateTimeImmutable
    {
        return $this->overrideDate(day: 1)->startOfDay();
    }

    public function startOfQuarter(): DateTimeImmutable
    {
        return $this->overrideDate(
            month: $this->monthEnum()->startOfQuarter()->value,
            day: 1,
        )->startOfDay();
    }

    public function startOfSemester(): DateTimeImmutable
    {
        return $this->overrideDate(
            month: $this->monthEnum()->startOfSemester()->value,
            day: 1,
        )->startOfDay();
    }

    public function startOfYear(): DateTimeImmutable
    {
        return $this->overrideDate(month: 1, day: 1)->startOfDay();
    }

    public function endOfSecond(): DateTimeImmutable
    {
        return $this->overrideTime(microsecond: TimeUnit::Microsecond->max());
    }

    public function endOfMinute(): DateTimeImmutable
    {
        return $this->overrideTime(second: TimeUnit::Second->max())->endOfSecond();
    }

    public function endOfHour(): DateTimeImmutable
    {
        return $this->overrideTime(minute: TimeUnit::Minute->max())->endOfMinute();
    }

    public function endOfDay(): DateTimeImmutable
    {
        return $this->overrideTime(TimeUnit::Hour->max())->endOfHour();
    }

    public function endOfWeek(DayOfWeek $startOfWeek = DayOfWeek::Sunday): DateTimeImmutable
    {
        $endOfWeek = $startOfWeek->yesterday();

        $new = $this->dayOfWeekEnum() !== $endOfWeek ? $this->assertModify($endOfWeek->fullName()) : $this;

        return $new->endOfDay();
    }

    public function endOfMonth(): DateTimeImmutable
    {
        return $this->overrideDate(
            day: $this->monthEnum()->days($this->leapYear()),
        )->endOfDay();
    }

    public function endOfQuarter(): DateTimeImmutable
    {
        return $this->overrideDate(month: $this->monthEnum()->endOfQuarter()->value, day: 1)->endOfMonth();
    }

    public function endOfSemester(): DateTimeImmutable
    {
        return $this->overrideDate(month: $this->monthEnum()->endOfSemester()->value, day: 1)->endOfMonth();
    }

    public function endOfYear(): DateTimeImmutable
    {
        return $this->overrideDate(month: 12)->endOfMonth();
    }
}
