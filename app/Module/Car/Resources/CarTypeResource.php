<?php

declare(strict_types=1);

namespace App\Module\Car\Resources;

use App\Http\Resources\BaseJsonResource;
use App\Module\Car\Models\CarType;

/**
 * @OA\Schema(
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="title", type="integer"),
 *     @OA\Property(property="capacity", type="string"),
 *     @OA\Property(property="volume", type="string")
 * )
 * @property CarType $resource
 */
final class CarTypeResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        return [
            'id'       => $this->resource->id,
            'title'    => $this->resource->title,
            'capacity' => $this->resource->capacity,
            'volume'   => $this->resource->volume,
        ];
    }
}
