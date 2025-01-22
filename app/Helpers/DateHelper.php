<?php

declare(strict_types=1);

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Carbon as IlluminateCarbon;

final class DateHelper
{
    public static function getDateWithTime(IlluminateCarbon|Carbon|null $date): ?string
    {
        return $date?->format('Y-m-d H:i');
    }

    public static function getISOFormat(IlluminateCarbon|Carbon|null $date): ?string
    {
        return $date?->format('Y-m-d\TH:i:s');
    }

    public static function getDate(IlluminateCarbon|Carbon|null $date): ?string
    {
        return $date?->format('Y-m-d');
    }

    public static function getTime(IlluminateCarbon|Carbon|null $date): ?string
    {
        return $date?->format('H:i');
    }
}
