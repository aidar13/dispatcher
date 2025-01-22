<?php

declare(strict_types=1);

namespace App\Module\Planning\Resources;

use App\Helpers\NumberHelper;
use App\Http\Resources\BaseJsonResource;
use App\Module\Planning\DTO\PlanningDTO;

/**
 * @OA\Schema(
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", description="Название сектора", example="Сектор 1"),
 *     @OA\Property(property="date", type="string", description="Дата планирования", example="2023-08-18"),
 *     @OA\Property(property="timeFrom", type="string", description="время от", example="10:00"),
 *     @OA\Property(property="timeTo", type="string", description="время до", example="14:00"),
 *     @OA\Property(property="stopsCount", type="integer", example="10"),
 *     @OA\Property(property="invoicesCount", type="integer", example="10"),
 *     @OA\Property(property="places", type="integer", example="10"),
 *     @OA\Property(property="weight", type="numeric", example="10.1"),
 *     @OA\Property(property="volumeWeight", type="numeric", example="10.2"),
 *     @OA\Property(
 *         property="invoices",
 *         ref="#/components/schemas/InvoiceResource"
 *     ),
 *     @OA\Property(
 *         property="containers",
 *         ref="#/components/schemas/ContainerResource"
 *     ),
 * )
 *
 * @property PlanningDTO $resource
 */
final class PlanningShowResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        return [
            'id'            => $this->resource->id,
            'name'          => $this->resource->name,
            'date'          => $request->get('date'),
            'timeFrom'      => $this->resource->timeFrom,
            'timeTo'        => $this->resource->timeTo,
            'stopsCount'    => $this->resource->stopsCount,
            'invoicesCount' => $this->resource->invoicesCount,
            'places'        => $this->resource->places,
            'weight'        => NumberHelper::getRounded($this->resource->weight),
            'volumeWeight'  => NumberHelper::getRounded($this->resource->volumeWeight),
            'invoices'      => $this->resource->invoices,
            'containers'    => ContainerResource::collection($this->resource->containers)
        ];
    }
}
