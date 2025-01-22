<?php

declare(strict_types=1);

namespace App\Module\User\Listeners;

use App\Module\User\Commands\UpdateUserCommand;
use App\Module\User\Events\UserCreatedEvent;

final class UserCreatedListener
{
    public function handle(UserCreatedEvent $event): void
    {
        if (app()->environment('production') || app()->environment('staging')) {
            dispatch(new UpdateUserCommand($event->userId));
        }
    }
}
