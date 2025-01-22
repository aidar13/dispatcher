<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Resources;

use App\Module\City\Resources\CityResource;
use App\Module\DispatcherSector\Models\DispatcherSector;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="cityId", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Алматы"),
 *     @OA\Property(property="description", type="string", example="Описание"),
 *     @OA\Property(property="coordinates", type="object", example="[[43.25900022611416, 76.9220136142039], [43.25900022611416, 76.92102558206726], [43.258843386009225, 76.91995163409266]]"),
 *     @OA\Property(property="polygon", type="string", example="43.25900022611416, 76.9220136142039, 43.25900022611416, 76.92102558206726, 43.258843386009225, 76.91995163409266"),
 *     @OA\Property(property="dispatcherIds", type="object", example="[1,2,3]"),
 *     @OA\Property(property="courierId", type="integer", example=1),
 *     @OA\Property(
 *         property="sectors",
 *         ref="#/components/schemas/SectorShowResource"
 *     ),
 *     @OA\Property(
 *         property="city",
 *         ref="#/components/schemas/CityResource"
 *     ),
 *     @OA\Property(
 *         property="dispatchers",
 *         ref="#/components/schemas/DispatcherSectorUserItemResource"
 *     ),
 * )
 *
 * @property DispatcherSector $resource
 */
final class DispatcherSectorResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'            => $this->resource->id,
            'name'          => $this->resource->name,
            'cityId'        => $this->resource->city_id,
            'description'   => $this->resource->description,
            'coordinates'   => $this->resource->coordinates,
            'polygon'       => $this->resource->polygon,
            'courierId'     => $this->resource?->courier_id,
            'city'          => CityResource::make($this->resource?->city),
            'sectors'       => SectorItemResource::collection($this->resource?->sectors),
            'dispatcherIds' => $this->resource->dispatcherSectorUsers->pluck('user_id')->toArray(),
            'dispatchers'   => DispatcherSectorUserItemResource::collection($this->resource->dispatcherSectorUsers)
        ];
    }
}
