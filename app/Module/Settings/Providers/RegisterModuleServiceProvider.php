<?php

declare(strict_types=1);

namespace App\Module\Settings\Providers;

use Illuminate\Support\ServiceProvider;

class RegisterModuleServiceProvider extends ServiceProvider
{
  /**
       * Register any application services.
       *
       * @return void
       */
    public function register(): void
    {
        $this->app->register(RepositorySettingsServiceProvider::class);
        $this->app->register(BindServiceProvider::class);
    }

      /**
       * Bootstrap any application services.
       *
       * @return void
       */
    public function boot()
    {
        //
    }
}
