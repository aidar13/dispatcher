<?php

declare(strict_types=1);

namespace App\Module\RabbitMQ\Contracts\Repositories;

use App\Module\RabbitMQ\Models\RabbitMQRequest;

interface DeleteRabbitMQRequestRepository
{
    public function delete(RabbitMQRequest $request): void;
}
