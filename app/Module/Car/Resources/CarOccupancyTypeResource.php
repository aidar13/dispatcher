<?php

declare(strict_types=1);

namespace App\Module\Car\Resources;

use App\Http\Resources\BaseJsonResource;
use App\Module\Car\Models\CarOccupancy;

/**
 * @OA\Schema (
 *     @OA\Property(property="id", type="int", example="1"),
 *     @OA\Property(property="title", type="string", example="Пустой"),
 *     @OA\Property(property="percent", type="string", example="0"),
 * )
 *
 * @property CarOccupancy $resource
 */
final class CarOccupancyTypeResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        return [
            'id'      => $this->resource?->id,
            'title'   => $this->resource->carOccupancyType?->title,
            'percent' => (int)$this->resource->carOccupancyType?->percent,
        ];
    }
}
