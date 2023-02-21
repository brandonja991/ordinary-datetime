<?php

declare(strict_types=1);

namespace Ordinary\DateTime;

use DateTimeImmutable as PHPDateTimeImmutableAlias;

class DateTimeImmutable extends PHPDateTimeImmutableAlias
{
    /*
     * override to define param with correct type - work around due to psalm in phpstorm
     * detecting param 1 type mismatch
     */
    /** @param array<string, mixed> $data */
    public function __unserialize(array $data): void
    {
        parent::__unserialize($data);
    }
}
