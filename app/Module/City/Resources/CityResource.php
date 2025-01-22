<?php

declare(strict_types=1);

namespace App\Module\City\Resources;

use App\Module\City\Models\City;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Алматы"),
 *     @OA\Property(property="regionId", type="integer", example=1),
 *     @OA\Property(property="typeId", type="integer", example=1),
 *     @OA\Property(property="code", type="string", example="130000ABC"),
 *     @OA\Property(property="coordinates", type="object", example="[[43.25900022611416, 76.9220136142039], [43.25900022611416, 76.92102558206726], [43.258843386009225, 76.91995163409266]]"),
 *     @OA\Property(property="latitude", type="string", example="41.11111"),
 *     @OA\Property(property="longitude", type="string", example="52.21234"),
 * )
 * @property City $resource
 */
final class CityResource extends JsonResource
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
                'regionId'    => $this->resource->region_id,
                'typeId'      => $this->resource->type_id,
                'code'        => $this->resource->code,
                'coordinates' => $this->resource->coordinates,
                'latitude'    => $this->resource->latitude,
                'longitude'   => $this->resource->longitude,
            ]);
        }

        return $data;
    }
}
