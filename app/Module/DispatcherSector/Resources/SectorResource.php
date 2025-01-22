<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Resources;

use App\Module\DispatcherSector\Models\Sector;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Сектор 1")
 * )
 * @property Sector $resource
 */
final class SectorResource extends JsonResource
{
    private bool $isExtended;

    public function __construct($resource, bool $isExtended = true)
    {
        parent::__construct($resource);
        $this->isExtended = $isExtended;
    }

    public function toArray($request): array
    {
        $data = [
            'id'   => $this->resource->id,
            'name' => $this->resource->name,
        ];

        if ($this->isExtended) {
            $data = array_merge($data, [
                'dispatcherSectorId' => $this->resource->dispatcher_sector_id,
                'coordinates'        => $this->resource->coordinates,
                'polygon'            => $this->resource->polygon,
                'color'              => $this->resource->color,
            ]);
        }

        return $data;
    }
}
