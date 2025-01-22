<?php

declare(strict_types=1);

namespace App\Module\Routing\Providers;

use Illuminate\Support\ServiceProvider;

final class RegisterModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(BindServiceProvider::class);
        $this->app->register(QueryServiceProvider::class);
        $this->app->register(CommandBusServiceProviders::class);
        $this->app->register(RepositoryServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
    }
}
