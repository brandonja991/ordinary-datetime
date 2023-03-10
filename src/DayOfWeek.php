<?php

declare(strict_types=1);

namespace Ordinary\DateTime;

enum DayOfWeek: int
{
    case Sunday = 0;
    case Monday = 1;
    case Tuesday = 2;
    case Wednesday = 3;
    case Thursday = 4;
    case Friday = 5;
    case Saturday = 6;

    public static function tryFromName(string $day): ?self
    {
        return array_combine(
            array_map(static fn (self $case) => $case->abbr(), self::cases()),
            self::cases(),
        )[ucfirst(substr($day, 0, 3))] ?? null;
    }

    public static function fromName(string $day): self
    {
        return self::tryFromName($day)
            ?? throw new UnexpectedValueException('Could not create day of week from string: ' . $day);
    }

    public function fullName(): string
    {
        return $this->name;
    }

    public function abbr(): string
    {
        return substr($this->fullName(), 0, 3);
    }

    public function iso8601DayNumber(): int
    {
        return match ($this) {
            self::Monday => 1,
            self::Tuesday => 2,
            self::Wednesday => 3,
            self::Thursday => 4,
            self::Friday => 5,
            self::Saturday => 6,
            self::Sunday => 7,
        };
    }

    public function tomorrow(): self
    {
        return match ($this) {
            self::Sunday => self::Monday,
            self::Monday => self::Tuesday,
            self::Tuesday => self::Wednesday,
            self::Wednesday => self::Thursday,
            self::Thursday => self::Friday,
            self::Friday => self::Saturday,
            self::Saturday => self::Sunday,
        };
    }

    public function yesterday(): self
    {
        return match ($this) {
            self::Sunday => self::Saturday,
            self::Monday => self::Sunday,
            self::Tuesday => self::Monday,
            self::Wednesday => self::Tuesday,
            self::Thursday => self::Wednesday,
            self::Friday => self::Thursday,
            self::Saturday => self::Friday,
        };
    }

    public function daysUntil(DayOfWeek $dayOfWeek): int
    {
        $currentDay = $this->value;
        $targetDay = $dayOfWeek->value;

        return $currentDay <= $targetDay
            ? $targetDay - $currentDay
            : 6 - $currentDay + $targetDay + 1;
    }

    public function daysSince(DayOfWeek $dayOfWeek): int
    {
        $currentDay = $this->value;
        $targetDay = $dayOfWeek->value;

        return $targetDay <= $currentDay
            ? $currentDay - $targetDay
            : 6 - $targetDay + $currentDay + 1;
    }
}
