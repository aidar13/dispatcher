<?php

declare(strict_types=1);

namespace App\Module\Settings\Services;

use App\Module\Settings\Contracts\Queries\SettingsQuery;
use App\Module\Settings\Contracts\Services\SettingsService as SettingsServiceContract;
use Illuminate\Support\Collection;

final class SettingsService implements SettingsServiceContract
{
    protected Collection $sectors;

    public function __construct(
        private readonly SettingsQuery $settingsQuery,
    ) {
    }

    public function isEnabled(string $key): bool
    {
        try {
            $settings = $this->settingsQuery->findByKey($key);

            return $settings->isEnabled();
        } catch (\Throwable $exception) {
            return false;
        }
    }
}
