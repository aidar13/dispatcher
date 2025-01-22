<?php

declare(strict_types=1);

namespace App\Module\Order\Commands;

final readonly class ChangeInvoiceTakeDateCommand
{
    public function __construct(
        public int $invoiceId,
        public string $takeDate,
        public int $periodId,
    ) {
    }
}
