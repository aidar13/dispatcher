<?php

declare(strict_types=1);

namespace App\Module\Delivery\Resources;

use App\Helpers\NumberHelper;
use App\Module\Delivery\DTO\PredictionReportDTO;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     @OA\Property(property="dispatcherSectorId", type="integer", example=1),
 *     @OA\Property(property="date", type="string", example="2023-08-11"),
 *     @OA\Property(property="factCount", type="integer", example="4"),
 *     @OA\Property(property="factWeight", type="float", example="10.21"),
 *     @OA\Property(property="factVolumeWeight", type="float", example="4.5"),
 *     @OA\Property(property="incomingCount", type="integer", example="4"),
 *     @OA\Property(property="incomingWeight", type="float", example="10.21"),
 *     @OA\Property(property="incomingVolumeWeight", type="float", example="4.5")
 * )
 * @property PredictionReportDTO $resource
 */
final class PredictionReportResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'dispatcherSectorId'   => $this->resource->dispatcherSectorId,
            'date'                 => $this->resource->date,
            'factCount'            => $this->resource->factCount,
            'factWeight'           => NumberHelper::getRounded($this->resource->factWeight),
            'factVolumeWeight'     => NumberHelper::getRounded($this->resource->factVolumeWeight),
            'incomingCount'        => $this->resource->incomingCount,
            'incomingWeight'       => NumberHelper::getRounded($this->resource->incomingWeight),
            'incomingVolumeWeight' => NumberHelper::getRounded($this->resource->incomingVolumeWeight),
        ];
    }
}
