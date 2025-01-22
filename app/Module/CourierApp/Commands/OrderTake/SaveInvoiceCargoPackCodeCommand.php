<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Commands\OrderTake;

final class SaveInvoiceCargoPackCodeCommand
{
    public function __construct(
        public readonly int $invoiceId,
        public readonly string $packCode
    ) {
    }
}
