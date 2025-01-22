<?php

declare(strict_types=1);

namespace App\Module\RabbitMQ\Contracts\Repositories;

use App\Module\RabbitMQ\Models\RabbitMQRequest;

interface ForceDeleteRabbitMQRequestRepository
{
    public function forceDelete(RabbitMQRequest $request): void;
}
