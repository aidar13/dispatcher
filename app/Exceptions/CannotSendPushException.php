<?php

namespace App\Exceptions;

use Exception;

class CannotSendPushException extends Exception
{
    const PREFIX = '[Push-Notification]:';
}
