<?php

namespace App\Module\User\Providers;

use App\Module\User\Events\UserCreatedEvent;
use App\Module\User\Listeners\UserCreatedListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UserCreatedEvent::class => [
            UserCreatedListener::class,
        ]
    ];
}
