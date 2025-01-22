<?php

declare(strict_types=1);

namespace App\Module\Gateway\Providers;

use Illuminate\Support\ServiceProvider;

class RegisterModuleServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RepositoryServiceProvider::class);
        $this->app->register(CommandBusServiceProviders::class);
    }
}
