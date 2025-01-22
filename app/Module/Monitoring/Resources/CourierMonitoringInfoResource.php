<?php

declare(strict_types=1);

namespace App\Module\Monitoring\Resources;

use App\Http\Resources\BaseJsonResource;
use App\Module\Courier\Models\Courier;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *     @OA\Property(property="id",type="number"),
 *     @OA\Property(property="fullName",type="string"),
 *     @OA\Property(property="totalTakes",type="number"),
 *     @OA\Property(property="totalDeliveries",type="number"),
 *     @OA\Property(property="cancelledTakes",type="number"),
 *     @OA\Property(property="cancelledDeliveries",type="number"),
 *     @OA\Property(property="remainedTakes",type="number"),
 *     @OA\Property(property="remainedDeliveries",type="number"),
 *     @OA\Property(property="completedTakes",type="number"),
 *     @OA\Property(property="completedDeliveries",type="number"),
 * )
 * @property-read Courier $resource
 */
final class CourierMonitoringInfoResource extends BaseJsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $totalTakes = 0;
        $totalDeliveries = 0;
        $cancelledTakes = 0;
        $cancelledDeliveries = 0;
        $remainedTakes = 0;
        $remainedDeliveries = 0;
        $completedTakes = 0;
        $completedDeliveries = 0;

        foreach ($this->resource->takes as $take) {
            $totalTakes     += 1;
            $cancelledTakes += (int)$take->isStatusCancelled();
            $remainedTakes  += (int)$take->isRemained();
            $completedTakes += (int)$take->isCompleted();
        }

        foreach ($this->resource->deliveries as $delivery) {
            $totalDeliveries     += 1;
            $cancelledDeliveries += (int)$delivery->isReturned();
            $remainedDeliveries  += (int)($delivery->isRemained());
            $completedDeliveries += (int)$delivery->isDelivered();
        }

        return [
            'id'                  => $this->resource->id,
            'fullName'            => $this->resource->full_name,
            'totalTakes'          => $totalTakes,
            'totalDeliveries'     => $totalDeliveries,
            'cancelledTakes'      => $cancelledTakes,
            'cancelledDeliveries' => $cancelledDeliveries,
            'remainedTakes'       => $remainedTakes,
            'remainedDeliveries'  => $remainedDeliveries,
            'completedTakes'      => $completedTakes,
            'completedDeliveries' => $completedDeliveries
        ];
    }
}
