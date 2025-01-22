<?php

declare(strict_types=1);

namespace App\Module\Courier\Commands\Integration;

use App\Module\Courier\DTO\CourierPaymentDTO;

final class CreateCourierPaymentCommand
{
    public function __construct(public readonly CourierPaymentDTO $DTO)
    {
    }
}
