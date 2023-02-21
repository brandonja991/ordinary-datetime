<?php

declare(strict_types=1);

namespace Ordinary\DateTime;

enum TimeUnit
{
    case Hour;
    case Minute;
    case Second;
    case Microsecond;

    public function max(): int
    {
        return match ($this) {
            self::Hour => 23,
            self::Minute, self::Second => 59,
            self::Microsecond => 999_999,
        };
    }

    public function validate(int $value): void
    {
        assert(
            $value > 0,
            new UnexpectedValueException('TimeUnit value must be greater than 0 - ' . $this->name),
        );

        $max = $this->max();

        assert(
            $value <= $max,
            new UnexpectedValueException(
                'TimeUnit value must be less than or equal to ' . $max . ' - ' . $this->name,
            ),
        );
    }
}
