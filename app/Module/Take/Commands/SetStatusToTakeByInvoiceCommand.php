<?php

declare(strict_types=1);

namespace App\Module\Take\Commands;

use App\Module\Take\DTO\SetStatusToTakeByInvoiceDTO;

final readonly class SetStatusToTakeByInvoiceCommand
{
    public function __construct(
        public SetStatusToTakeByInvoiceDTO $DTO,
    ) {
    }
}
