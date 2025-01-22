<?php

declare(strict_types=1);

namespace App\Module\Delivery\Resources;

use App\Helpers\NumberHelper;
use App\Module\Delivery\DTO\CarPredictionItemDTO;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     @OA\Property(property="waveId", type="integer", description="Айди волны", example="1"),
 *     @OA\Property(property="waveTitle", type="string", description="Название волны", example="Wave 1"),
 *     @OA\Property(property="sectorId", type="integer", description="Айди сектора", example="1"),
 *     @OA\Property(property="sectorName", type="integer", description="Название сектора", example="U"),
 *     @OA\Property(property="truck", type="array", @OA\Items(ref="#/components/schemas/CarPredictionResource")),
 *     @OA\Property(property="passanger", type="array", @OA\Items(ref="#/components/schemas/CarPredictionResource")),
 *     @OA\Property(property="invoicesCount", type="integer", description="количество накладных", example="10"),
 *     @OA\Property(property="stopsCount", type="integer", description="количество стопов", example="4"),
 *     @OA\Property(property="weight", type="numeric", description="Физ вес", example="12000"),
 *     @OA\Property(property="volumeWeight", type="numeric", description="Объемный вес", example="12000"),
 * )
 *
 * @property CarPredictionItemDTO $resource
 */
final class CarPredictionItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'waveId'        => $this->resource->waveId,
            'waveTitle'     => $this->resource->waveTitle,
            'sectorId'      => $this->resource->sectorId,
            'sectorName'    => $this->resource->sectorName,
            'truck'         => new CarPredictionResource($this->resource->truck),
            'passanger'     => new CarPredictionResource($this->resource->passanger),
            'invoicesCount' => $this->resource->invoicesCount,
            'stopsCount'    => $this->resource->stopsCount,
            'weight'        => NumberHelper::getRounded($this->resource->weight),
            'volumeWeight'  => NumberHelper::getRounded($this->resource->volumeWeight),
        ];
    }
}
