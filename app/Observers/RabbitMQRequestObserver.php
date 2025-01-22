<?php

declare(strict_types=1);

namespace App\Observers;

use App\Module\RabbitMQ\Events\RabbitMQRequestCreatedEvent;
use App\Module\RabbitMQ\Models\RabbitMQRequest;
use Illuminate\Support\Facades\Log;

final class RabbitMQRequestObserver
{
    public bool $afterCommit = true;

    public function created(RabbitMQRequest $request): void
    {
        Log::info('RabbitMQRequest создан: ' . $request->id);

        event(new RabbitMQRequestCreatedEvent($request->id));
    }
}
