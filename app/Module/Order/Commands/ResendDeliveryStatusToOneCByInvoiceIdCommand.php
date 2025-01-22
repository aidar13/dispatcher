<?php

declare(strict_types=1);

namespace App\Module\Order\Commands;

final readonly class ResendDeliveryStatusToOneCByInvoiceIdCommand
{
    public function __construct(
        public int $invoiceId,
    ) {
    }
}
