<?php

declare(strict_types=1);

namespace App\Module\RabbitMQ\Commands;

use Carbon\Carbon;

final class RabbitMQRequestStatusCommand
{
    public function __construct(
        public int $requestId,
        public ?Carbon $successAt,
        public ?Carbon $failedAt,
        public ?string $message
    ) {
    }
}
