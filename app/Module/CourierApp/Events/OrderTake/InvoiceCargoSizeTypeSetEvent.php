<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Events\OrderTake;

final class InvoiceCargoSizeTypeSetEvent
{
    public function __construct(
        public readonly int $invoiceCargoId
    ) {
    }
}
