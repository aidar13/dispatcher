<?php

declare(strict_types=1);

namespace App\Module\Order\Events;

final readonly class InvoiceSectorsUpdatedEvent
{
    public function __construct(
        public int $id
    ) {
    }
}
