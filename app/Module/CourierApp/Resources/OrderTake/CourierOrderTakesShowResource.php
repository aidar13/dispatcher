<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Resources\OrderTake;

use App\Helpers\CargoHelper;
use App\Helpers\DateHelper;
use App\Helpers\NumberHelper;
use App\Module\Company\Resources\CompanyResource;
use App\Module\CourierApp\Resources\CourierPayment\CourierPaymentResource;
use App\Module\CourierApp\Resources\ShortComingFiles\ShortComingFilesResource;
use App\Module\Status\Resources\WaitListStatusResource;
use App\Module\Take\Models\OrderTake;
use App\Module\Take\Resources\OrderPeriodResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @OA\Schema(
 *     @OA\Property(property="data", ref="#/components/schemas/CourierTakeInvoiceResource"),
 *     @OA\Property(property="id", type="integer", description="Id забора", example=1),
 *     @OA\Property(property="orderId", type="integer", description="Id заказа", example=1),
 *     @OA\Property(property="volume", type="float", description="Объем", example=1),
 *     @OA\Property(property="volumeWeight", type="float", description="Физ вес", example=1),
 *     @OA\Property(property="places", type="integer", description="Места", example=1),
 *     @OA\Property(property="takeDate", type="string", format="date-time", description="Дата забора", example="2024-01-08"),
 *     @OA\Property(property="orderNumber", type="string", description="Номер заказа", example="001703"),
 *     @OA\Property(property="productName", type="string", description="Наименование товара", example="Косметика"),
 *     @OA\Property(property="takenAt", type="string", description="Дата забора", example="2023-01-01 12:48"),
 *     @OA\Property(property="states", type="bool", description="Я приехал", example="true"),
 *     @OA\Property(property="comment", type="string", description="Комментарий отправителя", example="Some comment"),
 *     @OA\Property(property="phone", type="string", description="Номер телефона отправителя", example="+77473232110"),
 *     @OA\Property(property="additionalPhone", type="string", description="Дополнительный номер телефона отправителя", example="+77473232111"),
 *     @OA\Property(property="address", type="string", description="Адрес", example="Толе би 101"),
 *     @OA\Property(property="latitude", type="string", description="Широта", example="43.123"),
 *     @OA\Property(property="longitude", type="string", description="Долгота", example="71.456"),
 *     @OA\Property(property="direction", type="string", description="Направление", example="Алматы - Астана"),
 *     @OA\Property(property="paymentTypeId", type="integer", description="ID типа плательщика", example="1"),
 *     @OA\Property(property="paymentTypeTitle", type="string", description="Плательщик", example="Отправителем"),
 *     @OA\Property(property="takePeriod", ref="#/components/schemas/OrderPeriodResource"),
 *     @OA\Property(property="company", ref="#/components/schemas/CompanyResource"),
 *     @OA\Property(property="courierPayments", ref="#/components/schemas/CourierPaymentResource"),
 *     @OA\Property(property="shortcomingFiles", ref="#/components/schemas/ShortComingFilesResource"),
 *     @OA\Property(property="checks", type="object", ref="#/components/schemas/CourierPaymentResource"),
 *     @OA\Property(property="waitList", type="object", ref="#/components/schemas/WaitListStatusResource"),
 * )
 */
final class CourierOrderTakesShowResource extends ResourceCollection
{
    public function toArray($request): array
    {
        /** @var OrderTake $orderTake */
        $orderTake = $this->resource->first();

        return [
            'data'             => CourierTakeInvoiceResource::collection($this->resource),
            'id'               => $orderTake->id,
            'orderId'          => $orderTake->order_id,
            'orderNumber'      => $orderTake->order->number,
            'productName'      => $orderTake->cargo?->product_name,
            'takenAt'          => DateHelper::getDateWithTime($orderTake->takenStatus?->created_at),
            'states'           => $orderTake->hasState(),
            'volume'           => NumberHelper::getRounded($this->resource->sum('volume')),
            'places'           => NumberHelper::getRounded($this->resource->sum('places')),
            'volumeWeight'     => CargoHelper::getVolumeInCubeMeterToWeightInKg($this->resource->sum('volume')),
            'takeDate'         => $orderTake->take_date,
            'comment'          => $orderTake->order->sender?->comment,
            'phone'            => $orderTake->customer?->phone,
            'additionalPhone'  => $orderTake->customer?->additional_phone,
            'address'          => $orderTake->customer?->address,
            'latitude'         => $orderTake->customer?->latitude != '' ? $orderTake->customer?->latitude : null,
            'longitude'        => $orderTake->customer?->longitude != '' ? $orderTake->customer?->longitude : null,
            'direction'        => $orderTake->getDirection(),
            'paymentTypeTitle' => $orderTake->invoice?->getPaymentTypeTitle(),
            'paymentTypeId'    => $orderTake->invoice?->payment_type,
            'checks'           => CourierPaymentResource::collection($orderTake->order->courierPayments ?? collect()),
            'waitList'         => new WaitListStatusResource($orderTake->order?->waitListStatuses?->last()),
            'takePeriod'       => new OrderPeriodResource($orderTake->invoice?->period),
            'company'          => new CompanyResource($orderTake->company),
            'shortcomingFiles' => new ShortComingFilesResource($orderTake->order),
        ];
    }
}
