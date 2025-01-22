<?php

declare(strict_types=1);

namespace App\Module\RabbitMQ\Contracts\Services;

use App\Module\RabbitMQ\Models\RabbitMQRequest;

interface RabbitMQService
{
    public function send(RabbitMQRequest $request);
}
