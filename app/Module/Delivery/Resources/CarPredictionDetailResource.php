<?php

declare(strict_types=1);

namespace App\Module\Delivery\Resources;

use App\Helpers\NumberHelper;
use App\Module\Delivery\DTO\CarPredictionDetailDTO;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     @OA\Property(property="autoCount", type="integer", description="количество машин", example="10"),
 *     @OA\Property(property="stopsCount", type="integer", description="количество стопов", example="4"),
 *     @OA\Property(property="weight", type="numeric", description="Физ вес", example="12000"),
 *     @OA\Property(property="volumeWeight", type="numeric", description="Объемный вес", example="12000"),
 * )
 *
 * @property CarPredictionDetailDTO $resource
 */
final class CarPredictionDetailResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'autoCount'    => $this->resource->autoCount,
            'stopsCount'   => $this->resource->stopsCount,
            'weight'       => NumberHelper::getRounded($this->resource->weight),
            'volumeWeight' => NumberHelper::getRounded($this->resource->volumeWeight)
        ];
    }
}
