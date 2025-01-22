<?php

declare(strict_types=1);

namespace App\Module\Take\Events;

final readonly class OrderTakeDateChangedEvent
{
    public function __construct(
        public int $invoiceId,
        public string $takeDate,
        public int $periodId,
    ) {
    }
}
