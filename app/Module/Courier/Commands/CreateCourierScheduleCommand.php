<?php

declare(strict_types=1);

namespace App\Module\Courier\Commands;

use App\Module\Courier\DTO\CreateCourierScheduleDTO;

final class CreateCourierScheduleCommand
{
    public function __construct(
        public readonly CreateCourierScheduleDTO $DTO
    ) {
    }
}
