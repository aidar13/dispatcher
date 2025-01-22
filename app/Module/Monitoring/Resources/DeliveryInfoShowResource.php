<?php

declare(strict_types=1);

namespace App\Module\Monitoring\Resources;

use App\Http\Resources\BaseJsonResource;
use App\Module\Monitoring\DTO\MonitoringDeliveryDTO;

/**
 * @OA\Schema(
 *     @OA\Property(property="totalDeliveriesInfoData",
 *         ref="#/components/schemas/DeliveryInfoResource"),
 *     @OA\Property(property="completedDeliveriesInfoData",
 *         ref="#/components/schemas/DeliveryInfoResource"),
 *     @OA\Property(property="remainedDeliveriesInfoData",
 *         ref="#/components/schemas/DeliveryInfoResource"),
 *     @OA\Property(property="cancelledDeliveriesInfoData",
 *         ref="#/components/schemas/DeliveryInfoResource"),
 * )
 * @property MonitoringDeliveryDTO $resource
 */
final class DeliveryInfoShowResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        return [
            'total'     => DeliveryInfoResource::collection($this->resource->total),
            'completed' => DeliveryInfoResource::collection($this->resource->completed),
            'remained'  => DeliveryInfoResource::collection($this->resource->remained),
            'cancelled' => DeliveryInfoResource::collection($this->resource->cancelled)
        ];
    }
}
