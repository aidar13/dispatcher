<?php

declare(strict_types=1);

namespace App\Module\Settings\Contracts\Services;

interface SettingsService
{
    public function isEnabled(string $key): bool;
}
