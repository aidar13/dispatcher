<?php

declare(strict_types=1);

namespace App\Module\Order\Commands;

final class UpdateInvoiceDeliveryDateCommand
{
    public function __construct(
        public readonly int $id,
        public readonly string $date
    ) {
    }
}
