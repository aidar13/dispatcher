<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Resources;

use App\Helpers\NumberHelper;
use App\Module\DispatcherSector\DTO\DispatcherWaveDTO;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     @OA\Property(property="id", type="int", example="1", description="ID волны"),
 *     @OA\Property(property="date", type="string", example="2023-08-16", description="Дата"),
 *     @OA\Property(property="invoicesCount", type="int", example="20", description="Кол-во накладных"),
 *     @OA\Property(property="stopsCount", type="int", example="15", description="Кол-во стопов"),
 *     @OA\Property(property="weight", type="numeric", example="200.12", description="Физ веc общий"),
 *     @OA\Property(property="volumeWeight", type="numeric", example="200.12", description="Объемный веc общий"),
 *     @OA\Property(
 *         property="sectors",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/SectorResource")
 *     ),
 *     @OA\Property(
 *         property="invoices",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/InvoiceResource")
 *     ),
 * )
 * @property DispatcherWaveDTO $resource
 */
final class WaveInvoicesResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'            => $this->resource->id,
            'date'          => $this->resource->date,
            'invoicesCount' => $this->resource->invoicesCount,
            'stopsCount'    => $this->resource->stopsCount,
            'weight'        => NumberHelper::getRounded($this->resource->weight),
            'volumeWeight'  => NumberHelper::getRounded($this->resource->volumeWeight),
            'sectors'       => $this->resource->sectors,
            'invoices'      => $this->resource->invoices,
        ];
    }
}
