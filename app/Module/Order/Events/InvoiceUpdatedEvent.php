<?php

namespace App\Module\Order\Events;

final class InvoiceUpdatedEvent
{
    public function __construct(public readonly int $invoiceId)
    {
    }
}
