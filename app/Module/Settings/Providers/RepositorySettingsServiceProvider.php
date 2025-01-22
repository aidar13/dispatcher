<?php

declare(strict_types=1);

namespace App\Module\Settings\Providers;

use App\Module\Settings\Contracts\Queries\SettingsQuery as SettingsQueryContract;
use App\Module\Settings\Queries\Cache\SettingsQuery as SettingsCacheQuery;
use App\Module\Settings\Queries\Eloquent\SettingsQuery;
use Illuminate\Support\ServiceProvider;

class RepositorySettingsServiceProvider extends ServiceProvider
{
    public array $bindings = [
        SettingsQueryContract::class => SettingsCacheQuery::class,
    ];

    public function register(): void
    {
        $this->app->when(SettingsCacheQuery::class)
            ->needs(SettingsQueryContract::class)
            ->give(SettingsQuery::class);
    }
}
