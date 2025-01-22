<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Resources\Delivery;

use App\Helpers\DateHelper;
use App\Module\Delivery\Models\Delivery;
use App\Module\Order\Resources\ShipmentTypeResource;
use App\Module\Status\Resources\RefStatusResource;
use App\Module\Status\Resources\StatusTypeResource;
use App\Module\Status\Resources\WaitListStatusResource;
use App\Module\Take\Resources\CustomerResource;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     @OA\Property(property="id", type="integer", description="ID доставки", example=1),
 *     @OA\Property(property="companyName", type="string", description="Имя компании", example="TOO Azat"),
 *     @OA\Property(property="address", type="string", description="Адрес", example="Толе би 101"),
 *     @OA\Property(property="createdAt", type="string", description="Дата создания доставки", example="2023-09-01 16:14"),
 *     @OA\Property(property="invoiceNumber", type="string", description="Номер накладной", example="SP00196614"),
 *     @OA\Property(property="invoiceId", type="integer", description="ID накладной", example=1234),
 *     @OA\Property(property="deliveredAt", type="string", description="Доставлено в", example="2023-09-01 16:14"),
 *     @OA\Property(property="weight", type="integer", description="Физ вес", example=1),
 *     @OA\Property(property="volumeWeight", type="integer", description="Обьемный вес", example=1),
 *     @OA\Property(property="places", type="integer", description="Кол-во мест", example=1),
 *     @OA\Property(property="latitude", type="string", description="Широта", example="78.183604"),
 *     @OA\Property(property="longitude", type="string", description="Долгота", example="69.353267"),
 *     @OA\Property(property="deliveryTime", type="string", description="Дата доставки", example="2024-01-01 23:45:45"),
 *     @OA\Property(property="position", type="integer", description="position", example="true"),
 *     @OA\Property(property="nearTakeInfoIds", type="array", description="Ids заборов рядом с доставкой",
 *     @OA\Items(type="enum", enum={1,2,3,4,5,6,7,8,9}),example={1,2}),
 *     @OA\Property(
 *         property="customer",
 *         ref="#/components/schemas/CustomerResource"
 *     ),
 *     @OA\Property(
 *         property="waitList",
 *         ref="#/components/schemas/WaitListStatusResource"
 *     ),
 *     @OA\Property(
 *         property="status",
 *         ref="#/components/schemas/StatusTypeResource"
 *     ),
 *     @OA\Property(
 *         property="waitListStatus",
 *         ref="#/components/schemas/RefStatusResource"
 *     ),
 *     @OA\Property(
 *         property="shipmentType",
 *         ref="#/components/schemas/ShipmentTypeResource"
 *     ),
 *     @OA\Property(
 *         property="details",
 *         ref="#/components/schemas/CourierDeliveryShowResource"
 *     ),
 * )
 *
 * @property Delivery $resource
 */
final class CourierDeliveryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'              => $this->resource->id,
            'companyName'     => $this->resource->company?->getName(),
            'createdAt'       => DateHelper::getDateWithTime($this->resource->created_at),
            'invoiceNumber'   => $this->resource->invoice?->invoice_number,
            'invoiceId'       => $this->resource->invoice?->id,
            'deliveredAt'     => $this->resource->delivered_at,
            'weight'          => $this->resource->weight,
            'places'          => $this->resource->places,
            'volumeWeight'    => $this->resource->volume_weight,
            'latitude'        => $this->resource->customer?->latitude,
            'longitude'       => $this->resource->customer?->longitude,
            'address'         => $this->resource->customer?->address,
            'deliveryTime'    => $this->resource->invoice?->sla_date,
            'nearTakeInfoIds' => $this->resource->getNearTakeInfoIds(),
            'position'        => $this->resource->routingItem?->position,
            'waitList'        => new WaitListStatusResource($this->resource->invoice?->waitListStatuses?->last()),
            'customer'        => new CustomerResource($this->resource->customer),
            'status'          => new StatusTypeResource($this->resource->status),
            'waitListStatus'  => new RefStatusResource($this->resource->refStatus),
            'shipmentType'    => new ShipmentTypeResource($this->resource->invoice?->shipmentType),
            'details'         => new CourierDeliveryShowResource($this->resource),
        ];
    }
}
