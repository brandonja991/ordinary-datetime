<?php

declare(strict_types=1);

namespace Ordinary\DateTime;

trait TimePartGetters
{
    abstract private function formatInt(string $format): int;

    public function microsecond(): int
    {
        return $this->formatInt('u');
    }

    public function second(): int
    {
        return $this->formatInt('s');
    }

    public function minute(): int
    {
        return $this->formatInt('i');
    }

    public function hour(): int
    {
        return $this->formatInt('H');
    }

    public function day(): int
    {
        return $this->formatInt('d');
    }

    public function dayOfWeek(): int
    {
        return $this->formatInt('w');
    }

    public function dayOfWeekEnum(): DayOfWeek
    {
        return DayOfWeek::from($this->dayOfWeek());
    }

    public function month(): int
    {
        return $this->formatInt('m');
    }

    public function monthEnum(): Month
    {
        return Month::from($this->month());
    }

    public function year(): int
    {
        return $this->formatInt('Y');
    }

    public function leapYear(): bool
    {
        return $this->formatInt('L') === 1;
    }
}
