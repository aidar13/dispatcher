<?php

declare(strict_types=1);

namespace App\Module\RabbitMQ\Queries;

use App\Module\RabbitMQ\Contracts\Queries\RabbitMQRequestQuery as RabbitMQRequestQueryContract;
use App\Module\RabbitMQ\Models\RabbitMQRequest;

final class RabbitMQRequestQuery implements RabbitMQRequestQueryContract
{
    public function getById(int $id): ?RabbitMQRequest
    {
        return RabbitMQRequest::find($id);
    }
}
