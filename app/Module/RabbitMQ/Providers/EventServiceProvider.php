<?php

namespace App\Module\RabbitMQ\Providers;

use App\Module\RabbitMQ\Events\RabbitMQRequestCreatedEvent;
use App\Module\RabbitMQ\Events\RabbitMQRequestStatusEvent;
use App\Module\RabbitMQ\Listeners\RabbitMQRequestCreatedListener;
use App\Module\RabbitMQ\Listeners\RabbitMQRequestStatusListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        RabbitMQRequestCreatedEvent::class => [
            RabbitMQRequestCreatedListener::class,
        ],
        RabbitMQRequestStatusEvent::class => [
            RabbitMQRequestStatusListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot(): void
    {
        parent::boot();
    }
}
