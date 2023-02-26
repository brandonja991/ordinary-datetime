# Ordinary DateTime
This library provides an extended DateTimeImmutable class that provides additional functionality.

## Getting Started
Install using composer.
```shell
composer require ordinary/datetime
```

## Usage
```php
$dateTime = new \Ordinary\DateTime\DateTimeImmutable();
var_dump([
    'Start of day' => $dateTime->startOfDay()->format('c'),
    'End of day' => $dateTime->endOfDay()->format('c'),
    'Is leap year' => $dateTime->leapYear(),
    'Is same minute' => $dateTime->isSameMinute(new DateTime()),
    'Is before' => $dateTime->isBefore(new DateTime()),
    'Current hour' => $dateTime->hour(),
    'Day of Week' => $dateTime->dayOfWeekEnum(),
]);
```