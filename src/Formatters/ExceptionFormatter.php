<?php

namespace Bushuev\Mattermost\Formatters;

use Exception;
use Throwable;

class ExceptionFormatter
{
    public function __construct(
        public Exception|Throwable $exception,
    )
    {
    }
}
