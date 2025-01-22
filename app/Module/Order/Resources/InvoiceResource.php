<?php

declare(strict_types=1);

namespace App\Module\Order\Resources;

use App\Helpers\NumberHelper;
use App\Module\Order\Models\Invoice;
use App\Module\Status\Resources\RefStatusResource;
use Exception;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="invoiceNumber", type="string", example="SP0000001"),
 *     @OA\Property(property="receiverSector", type="string", example="А"),
 *     @OA\Property(property="weight", type="string", example="12.3"),
 *     @OA\Property(property="places", type="string", example="1"),
 *     @OA\Property(property="volumeWeight", type="string", example="1.2"),
 *     @OA\Property(property="latitude", type="string", example="41.11111"),
 *     @OA\Property(property="longitude", type="string", example="52.21234"),
 *     @OA\Property(property="sectorId", type="integer", example=3),
 *     @OA\Property(property="sectorName", type="string", example="Сектор А"),
 *     @OA\Property(property="deliveryTime", type="string", example="12:00"),
 *     @OA\Property(property="timer", type="numeric", example="300", description="Таймер в минутах"),
 *     @OA\Property(property="hasAdditionalService", type="bool", example="true"),
 *     @OA\Property(property="status", type="numeric", example="1"),
 *     @OA\Property(property="stopsCount", type="integer", example=1),
 *     @OA\Property(
 *         property="waitList",
 *         ref="#/components/schemas/RefStatusResource"
 *     )
 * )
 * @property Invoice $resource
 */
final class InvoiceResource extends JsonResource
{
    private bool $isExtended;
    private ?string $previousCoordinate;

    public function __construct($resource, bool $isExtended = true, ?string $previousCoordinate = null)
    {
        parent::__construct($resource);
        $this->isExtended         = $isExtended;
        $this->previousCoordinate = $previousCoordinate;
    }

    /**
     * @throws Exception
     */
    public function toArray($request): array
    {
        $data = [
            'id'            => $this->resource->id,
            'invoiceNumber' => $this->resource->invoice_number,
            'places'        => $this->resource->cargo?->places,
            'weight'        => NumberHelper::getRounded($this->resource->cargo?->weight),
            'volumeWeight'  => NumberHelper::getRounded($this->resource->cargo?->volume_weight),
            'latitude'      => $this->resource->receiver?->latitude,
            'longitude'     => $this->resource->receiver?->longitude,
            'sectorId'      => $this->resource->receiver?->sector_id,
            'sectorName'    => $this->resource->receiver?->sector?->name,
            'shipmentType'  => $this->resource->shipment_id,
            'status'        => $this->resource->getStatusForWave(),
            'statusId'      => $this->resource->getStatusIdWave(),
            'stopsCount'    => $this->resource->getStopsWithPreviousInvoiceCoordinate($this->previousCoordinate),
            'waitList'      => $this->resource->waitListStatus ?: new RefStatusResource($this->resource->waitListStatus)
        ];

        if ($this->isExtended) {
            $data = array_merge($data, [
                'receiverSector'       => $this->resource->receiver?->sector?->name,
                'deliveryTime'         => $this->resource->getDeliveryTime(),
                'timer'                => $this->resource->getTimerTime(),
                'hasAdditionalService' => $this->resource->hasAdditionalServices(),
            ]);
        }

        return $data;
    }
}
