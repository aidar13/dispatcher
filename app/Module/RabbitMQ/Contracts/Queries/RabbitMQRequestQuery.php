<?php

declare(strict_types=1);

namespace App\Module\RabbitMQ\Contracts\Queries;

use App\Module\RabbitMQ\Models\RabbitMQRequest;

interface RabbitMQRequestQuery
{
    public function getById(int $id): ?RabbitMQRequest;
}
