<?php

declare(strict_types=1);

namespace App\Module\RabbitMQ\Providers;

use App\Module\RabbitMQ\Contracts\Queries\RabbitMQRequestQuery as RabbitMQRequestQueryContract;
use App\Module\RabbitMQ\Queries\RabbitMQRequestQuery;
use Illuminate\Support\ServiceProvider;

final class QueryServiceProvider extends ServiceProvider
{
    public array $bindings = [
        RabbitMQRequestQueryContract::class => RabbitMQRequestQuery::class,
    ];
}
