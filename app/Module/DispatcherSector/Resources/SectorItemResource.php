<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Resources;

use App\Module\DispatcherSector\Models\Sector;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Сектор 1"),
 *     @OA\Property(property="dispatcherSectorId", type="integer", example=1),
 *     @OA\Property(property="coordinates", type="object", example="[[43.25900022611416, 76.9220136142039], [43.25900022611416, 76.92102558206726], [43.258843386009225, 76.91995163409266]]"),
 *     @OA\Property(property="polygon", type="string", example="43.25900022611416, 76.9220136142039, 43.25900022611416, 76.92102558206726, 43.258843386009225, 76.91995163409266"),
 *     @OA\Property(property="color", type="string", example="#68CCCA"),
 * )
 * @property Sector $resource
 */
final class SectorItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                   => $this->resource->id,
            'name'                 => $this->resource->name,
            'dispatcherSectorId'   => $this->resource->dispatcher_sector_id,
            'coordinates'          => $this->resource->coordinates,
            'polygon'              => $this->resource->polygon,
            'color'                => $this->resource->color,
        ];
    }
}
