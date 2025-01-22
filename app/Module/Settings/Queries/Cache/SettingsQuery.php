<?php

declare(strict_types=1);

namespace App\Module\Settings\Queries\Cache;

use App\Constants\CacheConstants;
use App\Module\Settings\Contracts\Queries\SettingsQuery as SettingsQueryContract;
use App\Module\Settings\Models\Settings;
use Illuminate\Support\Facades\Cache;

final readonly class SettingsQuery implements SettingsQueryContract
{
    public function __construct(private SettingsQueryContract $query)
    {
    }

    public function findByKey(string $key): Settings
    {
        return Cache::remember(
            CacheConstants::getCacheKeyById(CacheConstants::SETTINGS_KEY_CACHE_KEY, $key),
            CacheConstants::CACHE_TTL_HOUR,
            fn() => $this->query->findByKey($key)
        );
    }
}
