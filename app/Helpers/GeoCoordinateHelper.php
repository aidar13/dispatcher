<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Collection;
use Location\Coordinate;
use Location\Distance\Vincenty;

final class GeoCoordinateHelper
{
    public const EARTH_RADIUS     = 6371;
    public const NEAR_DISTANCE_KM = 1;

    public static function isNearDistance(
        float $latitude1,
        float $longitude1,
        float $latitude2,
        float $longitude2,
    ): bool {
        return self::getHaversineDistance($latitude1, $longitude1, $latitude2, $longitude2) <= self::NEAR_DISTANCE_KM;
    }

    /**
     * @param Collection $invoices
     * @return Collection
     */
    public static function sortByDistance(Collection $invoices): Collection
    {
        $calculator = new Vincenty();

        $invoice            = $invoices->shift();
        $sortedInvoiceIds[] = $invoice?->id;

        while ($invoices->isNotEmpty()) {
            $minDistance = PHP_FLOAT_MAX;

            foreach ($invoices as $key => $item) {
                $fromPoint = new Coordinate((float)$invoice->latitude, (float)$invoice->longitude);
                $toPoint   = new Coordinate((float)$item->latitude, (float)$item->longitude);

                $distance = $calculator->getDistance($fromPoint, $toPoint);

                if ($distance < $minDistance) {
                    $minDistance  = $distance;
                    $nearestIndex = $key;
                }
            }

            $invoice            = $invoices->pull($nearestIndex);
            $sortedInvoiceIds[] = $invoice->id;
        }

        return collect($sortedInvoiceIds);
    }

    public static function getHaversineDistance($latitude1, $longitude1, $latitude2, $longitude2): float|int|null
    {
        if (!$latitude1 || !$longitude1 || !$latitude2 || !$longitude2) {
            return null;
        }

        $latitudeDifference  = deg2rad($latitude2 - $latitude1);
        $longitudeDifference = deg2rad($longitude2 - $longitude1);

        $intermediateValue = sin($latitudeDifference / 2) * sin($latitudeDifference / 2)
            + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2))
            * sin($longitudeDifference / 2) * sin($longitudeDifference / 2);

        $angleBetweenPoints = 2 * atan2(sqrt($intermediateValue), sqrt(1 - $intermediateValue));

        return round(self::EARTH_RADIUS * $angleBetweenPoints, 1);
    }
}
