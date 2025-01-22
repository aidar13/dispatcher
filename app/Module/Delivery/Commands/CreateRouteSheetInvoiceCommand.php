<?php

declare(strict_types=1);

namespace App\Module\Delivery\Commands;

final class CreateRouteSheetInvoiceCommand
{
    public function __construct(
        public readonly int $routeSheetId,
        public readonly int $invoiceId,
    ) {
    }
}
