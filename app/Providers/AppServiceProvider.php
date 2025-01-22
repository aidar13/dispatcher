<?php

namespace App\Providers;

use App\Module\OneC\Contracts\Integration\Integration1CConfigContract;
use App\Module\OneC\Services\Integration1CConfigService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * @psalm-suppress UndefinedInterfaceMethod
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        //Services
        $this->app->bind(Integration1CConfigContract::class, Integration1CConfigService::class);

        if ($this->app->isLocal()) {
            $this->app->register(\Clockwork\Support\Laravel\ClockworkServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        if (app()->environment('staging') && app()->environment('production')) {
            DB::statement('SET SESSION innodb_lock_wait_timeout = 10;');
        }
    }
}
