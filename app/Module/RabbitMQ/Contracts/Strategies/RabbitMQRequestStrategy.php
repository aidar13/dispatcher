<?php

namespace App\Module\RabbitMQ\Contracts\Strategies;

use App\Module\RabbitMQ\Models\RabbitMQRequest;

interface RabbitMQRequestStrategy
{
    public function isExecutable(string $channel): bool;

    public function execute(RabbitMQRequest $request): void;
}
