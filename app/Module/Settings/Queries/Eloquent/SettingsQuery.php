<?php

declare(strict_types=1);

namespace App\Module\Settings\Queries\Eloquent;

use App\Module\Settings\Contracts\Queries\SettingsQuery as SettingsQueryContract;
use App\Module\Settings\Exceptions\SettingsNotFoundException;
use App\Module\Settings\Models\Settings;

final class SettingsQuery implements SettingsQueryContract
{
    public function findByKey(string $key): Settings
    {
        /** @var Settings $settings */
        $settings = Settings::query()->where('key', $key)->first();

        if (!$settings) {
            throw new SettingsNotFoundException('Не найдено такой ключь');
        }

        return $settings;
    }
}
