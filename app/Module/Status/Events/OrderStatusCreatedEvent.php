<?php

declare(strict_types=1);

namespace App\Module\Status\Events;

final readonly class OrderStatusCreatedEvent
{
    public function __construct(
        public int $invoiceId,
        public int $code,
        public int $statusId,
        public ?int $sourceId
    ) {
    }
}
