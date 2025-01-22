<?php

namespace App\Module\Take\Commands;

final readonly class ChangeTakeDateByInvoiceIdCommand
{
    public function __construct(
        public int $invoiceId,
        public string $newDate,
        public int $periodId,
    ) {
    }
}
