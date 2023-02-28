<?php

declare(strict_types=1);

namespace Ordinary\DateTime;

use DateTimeImmutable as PHPDateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Exception;

class DateTimeImmutable extends PHPDateTimeImmutable
{
    use OrdinalTimeComparison;
    use TimePartGetters;
    use TimeModifiers;
    use RoundedTimeComparison;

    public static function assertSelf(DateTimeInterface $dateTime): self
    {
        /** @var self $result not returned directly to work around createTimeInterface result not being static */
        $result =  $dateTime instanceof self
            ? $dateTime
            : self::createFromInterface($dateTime);

        return $result;
    }

    public static function earliest(?DateTimeZone $timeZone = null): self
    {
        return new self('0000-01-01T00:00:00', $timeZone);
    }

    public static function latest(?DateTimeZone $timeZone = null): self
    {
        return new self('9999-12-31T23:59:59.999999', $timeZone);
    }

    /**
     * @param array<string, mixed> $data
     *
     * note: override to define param with correct type - work around due to psalm in phpstorm
     * detecting param 1 type mismatch
     */
    public function __unserialize(array $data): void
    {
        parent::__unserialize($data);
    }

    public function overrideTime(
        ?int $hour = null,
        ?int $minute = null,
        ?int $second = null,
        ?int $microsecond = null,
    ): self {
        if ($hour) {
            TimeUnit::Hour->validate($hour);
        }

        if ($minute) {
            TimeUnit::Minute->validate($minute);
        }

        if ($second) {
            TimeUnit::Second->validate($second);
        }

        if ($microsecond) {
            TimeUnit::Microsecond->validate($microsecond);
        }

        return $this->setTime(
            $hour ?? $this->hour(),
            $minute ?? $this->minute(),
            $second ?? $this->second(),
            $microsecond ?? $this->microsecond(),
        );
    }

    public function overrideDate(?int $year = null, ?int $month = null, ?int $day = null): self
    {
        if ([$year, $month, $day] === [null, null, null]) {
            // short circuit if no changes
            return $this;
        }

        $effectiveYear = $year ?? $this->year();
        $effectiveMonth = $month ?? $this->month();
        $effectiveDay = $day ?? $this->day();

        if ($year !== null) {
            assert($year >= 0, new UnexpectedValueException('Unexpected negative year'));
            assert($year <= 9999, new UnexpectedValueException('Unexpected year greater than 9999'));
        }

        if ($month !== null) {
            $monthVO = Month::tryFrom($month)
                ?? throw new UnexpectedValueException('Invalid month: ' . $month);
        }

        $monthVO ??= $this->monthEnum();
        // always validate the day since a change to year, month, or day can invalidate the day
        $monthVO->validateDay($effectiveDay, (new DateTimeImmutable("$effectiveYear-01-01"))->leapYear());

        return $this->setDate(
            $effectiveYear,
            $effectiveMonth,
            $effectiveDay,
        );
    }

    public function assertModify(string $modifier): self
    {
        try {
            $new = $this->modify($modifier);
        } catch (Exception $e) {
            throw new UnexpectedValueException(
                'Failed to modify date time using modifier: ' . $modifier,
                previous: $e,
            );
        }

        assert(
            $new instanceof self,
            new UnexpectedValueException(
                'Failed to modify date time using modifier: ' . $modifier,
            ),
        );

        return $new;
    }

    private function formatInt(string $format): int
    {
        return (int) $this->format($format);
    }
}
