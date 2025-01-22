<?php

declare(strict_types=1);

namespace App\Module\RabbitMQ\Providers;

use App\Module\RabbitMQ\Contracts\Services\RabbitMQService as RabbitMQServiceContract;
use App\Module\RabbitMQ\Models\RabbitMQRequest;
use App\Module\RabbitMQ\Services\RabbitMQService;
use Illuminate\Support\ServiceProvider;

class BindServiceProvider extends ServiceProvider
{
    public array $bindings = [
        RabbitMQServiceContract::class => RabbitMQService::class,
    ];

    public function register(): void
    {
        $this->app->tag(RabbitMQRequest::STRATEGIES, 'strategies');
        $this->app->when(RabbitMQService::class)
            ->needs('$strategies')
            ->giveTagged('strategies');
    }
}
