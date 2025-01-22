<?php

declare(strict_types=1);

namespace App\Module\RabbitMQ\Contracts\Repositories;

use App\Module\RabbitMQ\Models\RabbitMQRequest;

interface CreateRabbitMQRequestRepository
{
    public function create(RabbitMQRequest $request): void;
}
