<?php

declare(strict_types=1);

namespace Ordinary\DateTime;

enum Month: int
{
    case January = 1;
    case February = 2;
    case March = 3;
    case April = 4;
    case May = 5;
    case June = 6;
    case July = 7;
    case August = 8;
    case September = 9;
    case October = 10;
    case November = 11;
    case December = 12;

    public static function tryFromName(string $month): ?self
    {
        return array_combine(
            array_map(static fn (self $case) => $case->abbr(), self::cases()),
            self::cases(),
        )[ucfirst(substr($month, 0, 3))] ?? null;
    }

    public static function fromName(string $month): self
    {
        return self::tryFromName($month)
            ?? throw new UnexpectedValueException('Could not create month from string: ' . $month);
    }

    public function fullName(): string
    {
        return $this->name;
    }

    public function abbr(): string
    {
        return substr($this->fullName(), 0, 3);
    }

    public function days(bool $leapYear = false): int
    {
        return match ($this) {
            self::January, self::March, self::May, self::July, self::August, self::October, self::December => 31,
            self::April, self::June, self::September, self::November => 30,
            self::February => $leapYear ? 29 : 28,
        };
    }

    public function validateDay(int $day, bool $leapYear = false): void
    {
        assert($day > 0, new UnexpectedValueException('Expecting day of month greater than 0'));

        $maxDays = $this->days($leapYear);
        assert(
            $day <= $maxDays,
            new UnexpectedValueException(
                'Expecting day less than or equal to ' . $maxDays . ' for month: ' . $this->fullName(),
            ),
        );
    }

    public function startOfQuarter(): self
    {
        return match ($this) {
            self::January, self::February, self::March => self::January,
            self::April, self::May, self::June => self::April,
            self::July, self::August, self::September => self::July,
            self::October, self::November, self::December => self::October,
        };
    }

    public function endOfQuarter(): self
    {
        return match ($this) {
            self::January, self::February, self::March => self::March,
            self::April, self::May, self::June => self::June,
            self::July, self::August, self::September => self::September,
            self::October, self::November, self::December => self::December,
        };
    }

    public function startOfSemester(): self
    {
        return match ($this) {
            self::January, self::February, self::March, self::April, self::May, self::June => self::January,
            self::July, self::August, self::September, self::October, self::November, self::December => self::July,
        };
    }

    public function endOfSemester(): self
    {
        return match ($this) {
            self::January, self::February, self::March, self::April, self::May, self::June => self::June,
            self::July, self::August, self::September, self::October, self::November, self::December => self::December,
        };
    }
}
