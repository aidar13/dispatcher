<?php

declare(strict_types=1);

namespace App\Module\Take\Events;

use App\Module\Status\Models\RefStatus;
use App\Module\Status\Models\StatusSource;

final readonly class OrderTakeAssignedToCourierEvent
{
    public function __construct(
        public int $orderTakeId,
        public int $userId,
        public int $statusCode = RefStatus::CODE_ASSIGNED_TO_COURIER,
        public int $statusSourceId = StatusSource::ID_DISPATCHER
    ) {
    }
}
