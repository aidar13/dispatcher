<?php

declare(strict_types=1);

namespace App\Module\Courier\Commands;

final readonly class UpdateCourierPhoneCommand
{
    public function __construct(
        public int $userId,
        public int $id,
        public string $phoneNumber
    ) {
    }
}
