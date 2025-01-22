<?php

declare(strict_types=1);

namespace App\Module\RabbitMQ\Providers;

use App\Module\RabbitMQ\Contracts\Repositories\CreateRabbitMQRequestRepository;
use App\Module\RabbitMQ\Contracts\Repositories\DeleteRabbitMQRequestRepository;
use App\Module\RabbitMQ\Contracts\Repositories\ForceDeleteRabbitMQRequestRepository;
use App\Module\RabbitMQ\Contracts\Repositories\UpdateRabbitMQRequestRepository;
use App\Module\RabbitMQ\Repositories\Eloquent\RabbitMQRequestRepository;
use Illuminate\Support\ServiceProvider;

final class RepositoryServiceProvider extends ServiceProvider
{
    public array $bindings = [
        CreateRabbitMQRequestRepository::class      => RabbitMQRequestRepository::class,
        UpdateRabbitMQRequestRepository::class      => RabbitMQRequestRepository::class,
        DeleteRabbitMQRequestRepository::class      => RabbitMQRequestRepository::class,
        ForceDeleteRabbitMQRequestRepository::class => RabbitMQRequestRepository::class,
    ];
}
