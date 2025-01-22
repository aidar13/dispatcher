<?php

declare(strict_types=1);

namespace App\Module\RabbitMQ\Services;

use App\Module\RabbitMQ\Contracts\Services\RabbitMQService as RabbitMQServiceContract;
use App\Module\RabbitMQ\Contracts\Strategies\RabbitMQRequestStrategy;
use App\Module\RabbitMQ\Models\RabbitMQRequest;

final class RabbitMQService implements RabbitMQServiceContract
{
    /**
     * @param array<RabbitMQRequestStrategy> $strategies
     */
    public function __construct(private readonly array $strategies)
    {
    }

    public function send(RabbitMQRequest $request): void
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->isExecutable($request->channel)) {
                $strategy->execute($request);
            }
        }
    }
}
