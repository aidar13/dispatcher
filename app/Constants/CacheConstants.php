<?php

declare(strict_types=1);

namespace App\Constants;

final class CacheConstants
{
    public const CACHE_TTL_DAY  = 86400;
    public const CACHE_TTL_HOUR = 86400;

    public const DISPATCHER_SECTOR_ALL_CACHE_KEY = 'dispatcher-sector-all-key';
    public const SETTINGS_KEY_CACHE_KEY          = 'settings-by-key';

    public static function getCacheKeyById(string $key, int|string $id): string
    {
        return "{$key}-{$id}";
    }
}
