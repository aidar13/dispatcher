<?php

declare(strict_types=1);

namespace App\Module\Order\Resources;

use App\Module\Order\Models\Invoice;
use App\Module\Status\Resources\RefStatusResource;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="invoiceNumber", type="string", example="SP0000001"),
 *     @OA\Property(property="weight", type="string", example="12.3"),
 *     @OA\Property(property="places", type="string", example="1"),
 *     @OA\Property(property="volumeWeight", type="string", example="1.2"),
 *     @OA\Property(property="position", type="numeric", example="1", description="порядок накладной в контейнере"),
 *     @OA\Property(property="latitude", type="string", example="41.11111"),
 *     @OA\Property(property="longitude", type="string", example="52.21234"),
 *     @OA\Property(property="sectorId", type="integer", example=3),
 *     @OA\Property(property="shortAddress", type="string", example="Толе би 101"),
 *     @OA\Property(property="shipmentType", type="numeric", example="1"),
 *     @OA\Property(property="timer", type="string", example="-12ч. 10м."),
 *     @OA\Property(property="status", type="numeric", example="1"),
 *     @OA\Property(property="statusId", type="numeric", example="1"),
 *     @OA\Property(property="stopsCount", type="integer", example=1),
 *     @OA\Property(property="problems", type="array",
 *         @OA\Items(
 *             type="string",
 *             example="Опаздывает"
 *         )
 *     ),
 *     @OA\Property(
 *         property="waitList",
 *         ref="#/components/schemas/RefStatusResource"
 *     )
 * )
 * @property Invoice $resource
 */
final class ContainerInvoiceResource extends JsonResource
{
    private ?string $previousCoordinate;

    public function __construct($resource, ?string $previousCoordinate = null)
    {
        parent::__construct($resource);
        $this->previousCoordinate = $previousCoordinate;
    }

    public function toArray($request): array
    {
        return [
            'id'            => $this->resource->id,
            'invoiceNumber' => $this->resource->invoice_number,
            'weight'        => $this->resource->cargo?->weight,
            'places'        => $this->resource->cargo?->places,
            'volumeWeight'  => $this->resource->cargo?->volume_weight,
            'position'      => $this->resource->position,
            'latitude'      => $this->resource->receiver?->latitude,
            'longitude'     => $this->resource->receiver?->longitude,
            'sectorId'      => $this->resource->receiver?->sector_id,
            'shortAddress'  => $this->resource->receiver?->getShortAddress(),
            'shipmentType'  => $this->resource->shipment_id,
            'timer'         => $this->resource->getTimerTime(),
            'status'        => $this->resource->getStatusForWave(),
            'statusId'      => $this->resource->getStatusIdWave(),
            'stopsCount'    => $this->resource->getStopsWithPreviousInvoiceCoordinate($this->previousCoordinate),
            'problems'      => $this->resource->getProblems(),
            'waitList'      => $this->resource->isWaitListOnDelivery()
                ? new RefStatusResource($this->resource->waitListStatus)
                : null,
        ];
    }
}
