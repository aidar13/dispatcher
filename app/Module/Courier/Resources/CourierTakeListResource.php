<?php

declare(strict_types=1);

namespace App\Module\Courier\Resources;

use App\Module\Car\Resources\CarOccupancyTypeResource;
use App\Module\Courier\Models\Courier;
use App\Module\DispatcherSector\Resources\DispatcherSectorItemResource;
use App\Module\DispatcherSector\Resources\SectorResource;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     @OA\Property(property="id", type="integer", example=1,description="Айди Курьера"),
 *     @OA\Property(property="fullName", type="string", example="Test Test",description="ФИО Курьера"),
 *     @OA\Property(property="phoneNumber", type="string", example="+77777777777",description="Номер Телефона Курьера"),
 *     @OA\Property(property="leftTakesCount", type="int", example="1",description="Осталось заборов"),
 *     @OA\Property(property="leftDeliveriesCount", type="int", example="2",description="Осталось доставок"),
 *     @OA\Property(property="dispatcherSector", type="object", ref="#/components/schemas/DispatcherSectorItemResource"),
 *     @OA\Property(property="carOccupancy", type="object", ref="#/components/schemas/CarOccupancyTypeResource"),
 *     @OA\Property(property="sectors", type="array", @OA\Items(ref="#/components/schemas/SectorResource")),
 * )
 *
 * @property Courier $resource
 */
final class CourierTakeListResource extends JsonResource
{
    public function toArray($request): array
    {
        $uniqueSectors = $this->resource->takes->pluck('customer.sector')
            ->merge($this->resource->deliveries->pluck('customer.sector'))
            ->unique()
            ->values();

        return [
            'id'                  => $this->resource->id,
            'fullName'            => $this->resource->full_name,
            'phoneNumber'         => $this->resource->phone_number,
            'leftTakesCount'      => $this->resource->takes->count(),
            'leftDeliveriesCount' => $this->resource->deliveries->count(),
            'dispatcherSector'    => new DispatcherSectorItemResource($this->resource->dispatcherSector),
            'carOccupancy'        => new CarOccupancyTypeResource($this->resource->carOccupancy),
            'sectors'             => $uniqueSectors->each(function ($sector) {
                new SectorResource($sector, false);
            }),
        ];
    }
}
