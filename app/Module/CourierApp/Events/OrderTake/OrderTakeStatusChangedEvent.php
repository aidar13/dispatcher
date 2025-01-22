<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Events\OrderTake;

use App\Module\Status\Models\StatusSource;

final readonly class OrderTakeStatusChangedEvent
{
    public function __construct(
        public int $orderTakeId,
        public int $userId,
        public int $statusCode,
        public int $statusSourceId = StatusSource::ID_DISPATCHER
    ) {
    }
}
