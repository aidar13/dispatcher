<?php

declare(strict_types=1);

namespace App\Helpers;

final class StringHelper
{
    public const REMOVE_ADDRESSES = [
        '/республика казахстан,/iu',
        '/республика казахстан/iu',
    ];

    public static function removeWords(string $string, array $words = self::REMOVE_ADDRESSES): string
    {
        return trim(preg_replace($words, '', $string));
    }
}
