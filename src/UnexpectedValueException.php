<?php

declare(strict_types=1);

namespace Ordinary\DateTime;

use UnexpectedValueException as PHPUnexpectedValueException;

class UnexpectedValueException extends PHPUnexpectedValueException implements DateTimeException
{
}
