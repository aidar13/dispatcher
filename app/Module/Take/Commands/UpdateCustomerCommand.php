<?php

declare(strict_types=1);

namespace App\Module\Take\Commands;

use App\Module\Take\DTO\CustomerDTO;

final class UpdateCustomerCommand
{
    public function __construct(
        public readonly int $id,
        public readonly CustomerDTO $DTO
    ) {
    }
}
