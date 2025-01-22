<?php

declare(strict_types=1);

namespace App\Module\Settings\Providers;

use App\Module\Settings\Contracts\Services\SettingsService as SettingsServiceContract;
use App\Module\Settings\Services\SettingsService;
use Illuminate\Support\ServiceProvider;

class BindServiceProvider extends ServiceProvider
{
    public array $bindings = [
        SettingsServiceContract::class => SettingsService::class,
    ];
}
