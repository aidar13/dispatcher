<?php

declare(strict_types=1);

namespace App\Module\Monitoring\Resources;

use App\Http\Resources\BaseJsonResource;
use App\Module\Monitoring\DTO\MonitoringTakeDTO;

/**
 * @OA\Schema(
 *     @OA\Property(property="total",
 *         ref="#/components/schemas/TakeInfoResource"),
 *     @OA\Property(property="completed",
 *         ref="#/components/schemas/TakeInfoResource"),
 *     @OA\Property(property="remained",
 *         ref="#/components/schemas/TakeInfoResource"),
 *     @OA\Property(property="cancelled",
 *         ref="#/components/schemas/TakeInfoResource"),
 * )
 * @property MonitoringTakeDTO $resource
 */
final class TakeInfoShowResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        return [
            'total'     => TakeInfoResource::collection($this->resource->total),
            'completed' => TakeInfoResource::collection($this->resource->completed),
            'remained'  => TakeInfoResource::collection($this->resource->remained),
            'cancelled' => TakeInfoResource::collection($this->resource->cancelled)
        ];
    }
}
