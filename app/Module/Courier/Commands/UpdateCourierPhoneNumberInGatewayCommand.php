<?php

declare(strict_types=1);

namespace App\Module\Courier\Commands;

final class UpdateCourierPhoneNumberInGatewayCommand
{
    public function __construct(public int $id)
    {
    }
}
