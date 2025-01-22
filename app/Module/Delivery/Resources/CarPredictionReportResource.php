<?php

declare(strict_types=1);

namespace App\Module\Delivery\Resources;

use App\Module\Delivery\DTO\CarPredictionReportDTO;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     @OA\Property(property="dispatcherSectorId", type="integer", example=1),
 *     @OA\Property(property="date", type="string", example="2023-08-11"),
 *     @OA\Property(property="cars", type="array", @OA\Items(ref="#/components/schemas/CarPredictionItemResource")),
 *     @OA\Property(property="truck", type="array", @OA\Items(ref="#/components/schemas/CarPredictionDetailResource")),
 *     @OA\Property(property="passanger", type="array", @OA\Items(ref="#/components/schemas/CarPredictionDetailResource")),
 * )
 * @property CarPredictionReportDTO $resource
 */
final class CarPredictionReportResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'dispatcherSectorId' => $this->resource->dispatcherSectorId,
            'date'               => $this->resource->date,
            'cars'               => CarPredictionItemResource::collection($this->resource->cars),
            'truck'              => new CarPredictionDetailResource($this->resource->truckDetail),
            'passanger'          => new CarPredictionDetailResource($this->resource->passangerDetail)
        ];
    }
}
