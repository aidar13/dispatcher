<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Resources\OrderTake;

use App\Helpers\CargoHelper;
use App\Helpers\NumberHelper;
use App\Module\Order\Resources\ShipmentTypeResource;
use App\Module\Status\Resources\WaitListStatusResource;
use App\Module\Take\Models\OrderTake;
use App\Module\Take\Resources\CustomerResource;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     @OA\Property(property="orderId", type="integer", description="ID заказа", example=1),
 *     @OA\Property(property="courierId", type="integer", description="ID курьера", example=1),
 *     @OA\Property(property="orderNumber", type="string", description="Номер заказа", example="0000012345"),
 *     @OA\Property(property="invoiceQuantity", type="integer", description="Кол-во накладных", example=1),
 *     @OA\Property(property="places", type="integer", description="Кол-во мест", example=1),
 *     @OA\Property(property="weight", type="integer", description="Физ вес", example=1),
 *     @OA\Property(property="volumeWeight", type="integer", description="Обьемный вес", example=1),
 *     @OA\Property(property="companyName", type="string", description="Имя компании", example="TOO Azat"),
 *     @OA\Property(property="companyId", type="integer", description="ID компании", example=1),
 *     @OA\Property(property="latitude", type="string", description="Широта", example="78.183604"),
 *     @OA\Property(property="longitude", type="string", description="Долгота", example="69.353267"),
 *     @OA\Property(property="isDelaying", type="bool", description="Задержка", example="true"),
 *     @OA\Property(property="position", type="integer", description="position", example="true"),
 *     @OA\Property(
 *         property="customer",
 *         ref="#/components/schemas/CustomerResource"
 *     ),
 *     @OA\Property(
 *         property="shipmentType",
 *         ref="#/components/schemas/ShipmentTypeResource"
 *     ),
 *     @OA\Property(
 *         property="waitList",
 *         ref="#/components/schemas/WaitListStatusResource"
 *       ),
 *     @OA\Property(
 *          property="details",
 *          ref="#/components/schemas/CourierOrderTakesShowResource"
 *      ),
 * )
 *
 * @property OrderTake $resource
 */
final class CourierOrderTakeResource extends JsonResource
{
    public function toArray($request): array
    {
        $order = $this->resource->order;

        return [
            'orderId'         => $this->resource->order_id,
            'courierId'       => $this->resource->courier_id,
            'orderNumber'     => $order->number,
            'invoiceQuantity' => $order?->invoices->count(),
            'places'          => $order->orderTakes->sum('places'),
            'weight'          => NumberHelper::getRounded($order->orderTakes->sum('weight')),
            'volumeWeight'    => CargoHelper::getVolumeInCubeMeterToWeightInKg($order->orderTakes->sum('volume')),
            'latitude'        => $this->resource->customer?->latitude,
            'longitude'       => $this->resource->customer?->longitude,
            'companyName'     => $this->resource->company?->getName(),
            'companyId'       => $this->resource->company?->id,
            'takenAt'         => $this->resource->takenStatus?->created_at->format('Y-m-d H:i:s'),
            'isDelaying'      => $this->resource->isDelaying(),
            'position'        => $this->resource->routingItem?->position,
            'waitList'        => new WaitListStatusResource($order?->waitListStatuses?->last()),
            'customer'        => new CustomerResource($this->resource->customer),
            'shipmentType'    => new ShipmentTypeResource($this->resource->shipmentType),
            'details'         => new CourierOrderTakesShowResource($order->orderTakes),
        ];
    }
}
