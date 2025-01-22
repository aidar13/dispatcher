<?php

declare(strict_types=1);

namespace App\Module\Delivery\Resources;

use App\Module\Delivery\DTO\CarPredictionDTO;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     @OA\Property(property="invoicesCount", type="integer", description="количество накладных", example="10"),
 *     @OA\Property(property="carCount", type="integer", description="количество стопов", example="4"),
 *     @OA\Property(property="stopsCount", type="integer", description="количество стопов", example="4"),
 *     @OA\Property(property="weight", type="numeric", description="Физ вес", example="12000"),
 *     @OA\Property(property="volumeWeight", type="numeric", description="Объемный вес", example="12000"),
 * )
 *
 * @property CarPredictionDTO $resource
 */
final class CarPredictionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'invoicesCount' => $this->resource->invoicesCount,
            'carCount'      => $this->resource->carCount,
            'stopsCount'    => $this->resource->stopsCount,
            'weight'        => $this->resource->weight,
            'volumeWeight'  => $this->resource->volumeWeight,
        ];
    }
}
