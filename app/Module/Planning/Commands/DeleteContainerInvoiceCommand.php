<?php

declare(strict_types=1);

namespace App\Module\Planning\Commands;

final class DeleteContainerInvoiceCommand
{
    public function __construct(
        public readonly int $containerId,
        public readonly int $invoiceId
    ) {
    }
}
