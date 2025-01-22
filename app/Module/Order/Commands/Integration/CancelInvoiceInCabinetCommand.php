<?php

declare(strict_types=1);

namespace App\Module\Order\Commands\Integration;

use App\Module\Order\DTO\CancelInvoiceDTO;

final class CancelInvoiceInCabinetCommand
{
    public function __construct(
        public readonly CancelInvoiceDTO $DTO,
    ) {
    }
}
