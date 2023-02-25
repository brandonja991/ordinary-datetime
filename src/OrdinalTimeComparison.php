<?php

declare(strict_types=1);

namespace Ordinary\DateTime;

use DateTimeInterface;

trait OrdinalTimeComparison
{
    /** Determine if the current instances time comes before the given time. */
    public function isBefore(DateTimeInterface $dateTime): bool
    {
        return $this < $dateTime;
    }

    /** Determine if the current instances time comes after the given time. */
    public function isAfter(DateTimeInterface $dateTime): bool
    {
        return $this > $dateTime;
    }

    /** Determine if the current instances time is the same or comes after the given time. */
    public function isAtOrAfter(DateTimeInterface $dateTime): bool
    {
        return $this >= $dateTime;
    }

    /** Determine if the current instances time is the same or comes before the given time. */
    public function isAtOrBefore(DateTimeInterface $dateTime): bool
    {
        return $this <= $dateTime;
    }
}
