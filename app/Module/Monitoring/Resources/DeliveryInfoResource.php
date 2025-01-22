<?php

declare(strict_types=1);

namespace App\Module\Monitoring\Resources;

use App\Http\Resources\BaseJsonResource;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

/**
 * @OA\Schema(
 *     @OA\Property(property="count",type="integer"),
 *     @OA\Property(property="sectorId",type="integer"),
 *     @OA\Property(property="name",type="string")
 * )
 */
final class DeliveryInfoResource extends BaseJsonResource
{
    /**
    * Transform the resource into an array.
    *
    * @param  Request $request
    * @return array
    */
    public function toArray($request): array
    {
        return [
            'count'    => Arr::get($this->resource, 'count'),
            'sectorId' => Arr::get($this->resource, 'sectorId'),
            'name'     => Arr::get($this->resource, 'sectorName'),
        ];
    }
}
