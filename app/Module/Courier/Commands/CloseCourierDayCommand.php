<?php

declare(strict_types=1);

namespace App\Module\Courier\Commands;

final class CloseCourierDayCommand
{
    public function __construct(
        public readonly int $courierId,
        public readonly int $userId,
        public readonly string $date
    ) {
    }
}
