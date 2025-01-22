<?php

declare(strict_types=1);

namespace App\Module\OneC\Providers;

use Illuminate\Support\ServiceProvider;

final class RegisterModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(RepositoryServiceProvider::class);
    }
}
