<?php

declare(strict_types=1);

namespace App\Module\Courier\Commands\Integration;

use App\Module\Courier\DTO\Integration\CourierLicenseDTO;

final readonly class UpdateCourierLicensesCommand
{
    public function __construct(
        public int $courierId,
        public CourierLicenseDTO $DTO,
    ) {
    }
}
