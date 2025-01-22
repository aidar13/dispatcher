<?php

declare(strict_types=1);

namespace App\Module\RabbitMQ\Repositories\Eloquent;

use App\Module\RabbitMQ\Contracts\Repositories\CreateRabbitMQRequestRepository;
use App\Module\RabbitMQ\Contracts\Repositories\DeleteRabbitMQRequestRepository;
use App\Module\RabbitMQ\Contracts\Repositories\ForceDeleteRabbitMQRequestRepository;
use App\Module\RabbitMQ\Contracts\Repositories\UpdateRabbitMQRequestRepository;
use App\Module\RabbitMQ\Models\RabbitMQRequest;
use Throwable;

final class RabbitMQRequestRepository implements
    CreateRabbitMQRequestRepository,
    UpdateRabbitMQRequestRepository,
    DeleteRabbitMQRequestRepository,
    ForceDeleteRabbitMQRequestRepository
{
    /**
     * @throws Throwable
     */
    public function create(RabbitMQRequest $request): void
    {
        $request->saveOrFail();
    }

    /**
     * @throws Throwable
     */
    public function update(RabbitMQRequest $request): void
    {
        $request->updateOrFail();
    }

    /**
     * @throws Throwable
     */
    public function delete(RabbitMQRequest $request): void
    {
        $request->deleteOrFail();
    }

    public function forceDelete(RabbitMQRequest $request): void
    {
        $request->forceDelete();
    }
}
