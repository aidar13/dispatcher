<?php

declare(strict_types=1);

namespace App\Module\Take\Commands;

use App\Module\Take\DTO\CustomerDTO;

final readonly class CreateCustomerCommand
{
    public function __construct(
        public CustomerDTO $DTO,
        public ?int $sectorId = null,
        public ?int $dispatcherSectorId = null,
    ) {
    }
}
