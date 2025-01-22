<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Events\Delivery;

use App\Module\Status\Models\StatusSource;

final readonly class DeliveryStatusChangedEvent
{
    public function __construct(
        public int $id,
        public int $userId,
        public int $statusCode,
        public int $statusSourceId = StatusSource::ID_DISPATCHER
    ) {
    }
}
