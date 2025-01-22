<?php

declare(strict_types=1);

namespace App\Helpers;

final class PolygonHelper
{
    /**
     * @param array|null $coordinates
     * @return string|null
     */
    public static function getPolygonFromCoordinates(?array $coordinates): ?string
    {
        $polygon = '';

        if (!$coordinates) {
            return null;
        }

        foreach ($coordinates as $point) {
            $polygon .= $point[0] . ' ' . $point[1] . ', ';
        }
        $polygon .= $coordinates[0][0] . ' ' . $coordinates[0][1];

        return $polygon;
    }
}
