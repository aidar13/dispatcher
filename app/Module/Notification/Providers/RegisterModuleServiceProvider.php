<?php

declare(strict_types=1);

namespace App\Module\Notification\Providers;

use Illuminate\Support\ServiceProvider;

final class RegisterModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(RepositoryServiceProvider::class);
        $this->app->register(CommandBusServiceProviders::class);
    }
}
