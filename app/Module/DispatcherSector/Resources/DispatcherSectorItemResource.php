<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Resources;

use App\Module\DispatcherSector\Models\DispatcherSector;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="cityId", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Алматы"),
 * )
 *
 * @property DispatcherSector $resource
 */
final class DispatcherSectorItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'     => $this->resource->id,
            'name'   => $this->resource->name,
            'cityId' => $this->resource->city_id
        ];
    }
}
