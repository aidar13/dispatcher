<?php

namespace App\Exceptions;

use Throwable;

class RequestOneCException extends \Exception
{
    const PREFIX = '[Ошибка в 1С]: ';

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct(self::PREFIX . $message, $code, $previous);
    }
}
