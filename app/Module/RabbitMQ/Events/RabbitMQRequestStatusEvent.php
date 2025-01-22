<?php

declare(strict_types=1);

namespace App\Module\RabbitMQ\Events;

use Carbon\Carbon;

final class RabbitMQRequestStatusEvent
{
    public function __construct(
        public int $requestId,
        public ?Carbon $successAt = null,
        public ?string $message = null,
        public ?Carbon $failedAt = null
    ) {
    }
}
