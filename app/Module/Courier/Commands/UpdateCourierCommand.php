<?php

declare(strict_types=1);

namespace App\Module\Courier\Commands;

use App\Module\Courier\DTO\UpdateCourierDTO;

final class UpdateCourierCommand
{
    public function __construct(
        public readonly int $id,
        public readonly UpdateCourierDTO $DTO
    ) {
    }
}
