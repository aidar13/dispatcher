<?php

declare(strict_types=1);

namespace App\Helpers;

final class NumberHelper
{
    public static function getRounded(float|int|null $number, int $precision = 2): float
    {
        return round(floatval($number), $precision);
    }
}
