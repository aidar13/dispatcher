<?php

namespace App\Exceptions;

use Exception;

class CannotSendEmailException extends Exception
{
    const PREFIX = '[Email-Notification]:';
}
