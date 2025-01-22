<?php

declare(strict_types=1);

namespace App\Module\Order\Resources;

use App\Helpers\NumberHelper;
use App\Http\Resources\BaseJsonResource;
use App\Module\Order\DTO\InvoicesDTO;

/**
 * @OA\Schema(
 *     @OA\Property(property="dispatcherSectorId", type="integer", example=1),
 *     @OA\Property(property="date", type="string", description="Дата планирования", example="2023-08-18"),
 *     @OA\Property(property="stopsCount", type="integer", example="10"),
 *     @OA\Property(property="invoicesCount", type="integer", example="10"),
 *     @OA\Property(property="places", type="integer", example="10"),
 *     @OA\Property(property="weight", type="numeric", example="10.1"),
 *     @OA\Property(property="volumeWeight", type="numeric", example="10.2"),
 *     @OA\Property(
 *         property="invoices",
 *         ref="#/components/schemas/InvoiceResource"
 *     ),
 * )
 *
 * @property InvoicesDTO $resource
 */
final class InvoicesResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        return [
            'dispatcherSectorId' => $this->resource->dispatcherSectorId,
            'date'               => $this->resource->date,
            'stopsCount'         => $this->resource->stopsCount,
            'invoicesCount'      => $this->resource->invoicesCount,
            'places'             => $this->resource->places,
            'weight'             => NumberHelper::getRounded($this->resource->weight),
            'volumeWeight'       => NumberHelper::getRounded($this->resource->volumeWeight),
            'invoices'           => $this->resource->invoices,
        ];
    }
}
