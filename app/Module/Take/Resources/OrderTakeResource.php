<?php

declare(strict_types=1);

namespace App\Module\Take\Resources;

use App\Helpers\CargoHelper;
use App\Helpers\DateHelper;
use App\Helpers\NumberHelper;
use App\Module\City\Resources\CityResource;
use App\Module\Courier\Resources\CourierShortInfoResource;
use App\Module\Status\Resources\RefStatusResource;
use App\Module\Status\Resources\StatusTypeResource;
use App\Module\Status\Resources\WaitListStatusResource;
use App\Module\Take\Models\OrderTake;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     @OA\Property(property="orderId", type="integer", example=1),
 *     @OA\Property(property="orderNumber", type="string", example="0000012345"),
 *     @OA\Property(property="invoiceQuantity", type="integer", example=1),
 *     @OA\Property(property="weight", type="numeric", description="Физ Вес", example="123.00"),
 *     @OA\Property(property="takeDate", type="string", description="Дата забора", example="2012-12-24"),
 *     @OA\Property(property="volumeWeight", type="numeric", description="Обьемный вес", example="123.00"),
 *     @OA\Property(property="companyName", type="string", description="Имя компании", example="TOO Azat"),
 *     @OA\Property(property="places", type="integer", description="Кол-во мест", example="10"),
 *     @OA\Property(property="latitude", type="string", example="78.183604"),
 *     @OA\Property(property="longitude", type="string", example="69.353267"),
 *     @OA\Property(property="hasPackType", type="boolean", example="true"),
 *     @OA\Property(property="takenAt", type="string", example="2023-01-01 12:48"),
 *     @OA\Property(property="callCenterComment", type="string", example="Comment"),
 *     @OA\Property(property="hasRoadCourierPayment", type="bool", example="true"),
 *     @OA\Property(property="hasAdditionalServices", type="bool", example="false", description="Наличие доп услуг при заборе"),
 *     @OA\Property(property="problems", type="array",
 *         @OA\Items(
 *             type="string",
 *             example="Перенос даты забора"
 *         )
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
 *         property="customer",
 *         ref="#/components/schemas/CustomerResource"
 *     ),
 *     @OA\Property(
 *         property="city",
 *         ref="#/components/schemas/CityResource"
 *     ),
 *     @OA\Property(
 *         property="courier",
 *         ref="#/components/schemas/CourierShortInfoResource"
 *     ),
 *     @OA\Property(
 *         property="takes",
 *         ref="#/components/schemas/TakeInvoiceResource"
 *     ),
 *     @OA\Property(
 *         property="period",
 *         ref="#/components/schemas/OrderPeriodResource"
 *     ),
 *     @OA\Property(
 *          property="waitList",
 *          ref="#/components/schemas/WaitListStatusResource"
 *      ),
 *     @OA\Property(property="waitListStatusHistory", type="array",
 *         @OA\Items(ref="#/components/schemas/WaitListStatusResource")
 *     )
 * )
 *
 * @property OrderTake $resource
 */
final class OrderTakeResource extends JsonResource
{
    /**
     * @psalm-suppress RedundantCondition
     * @psalm-suppress TypeDoesNotContainNull
     */
    public function toArray($request): array
    {
        $order = $this->resource->order;

        return [
            'orderId'                  => $this->resource->order_id,
            'orderNumber'              => $this->resource->getOrderNumber(),
            'takeDate'                 => $this->resource->take_date,
            'invoiceQuantity'          => $order?->invoices->count(),
            'places'                   => $order?->orderTakes->sum('places'),
            'companyName'              => $this->resource->company?->short_name,
            'companyId'                => $this->resource->company?->id,
            'latitude'                 => $this->resource->customer?->latitude,
            'longitude'                => $this->resource->customer?->longitude,
            'problems'                 => $this->resource->order->getProblems(),
            'hasPackType'              => $order?->hasPackType(),
            'weight'                   => NumberHelper::getRounded($order?->orderTakes->sum('weight')),
            'volumeWeight'             => CargoHelper::getVolumeInCubeMeterToWeightInKg($order?->orderTakes->sum('volume')),
            'takenAt'                  => DateHelper::getDateWithTime($this->resource->takenStatus?->created_at),
            'hasRoadCourierPayment'    => $order?->courierPaymentsForRoad()?->isNotEmpty(),
            'hasParkingCourierPayment' => $order?->courierPaymentsForParking()?->isNotEmpty(),
            'status'                   => new StatusTypeResource($this->resource->status),
            'waitListStatus'           => new RefStatusResource($this->resource->waitListStatus),
            'customer'                 => new CustomerResource($this->resource->customer),
            'city'                     => new CityResource($this->resource->city, false),
            'courier'                  => new CourierShortInfoResource($this->resource->courier),
            'period'                   => new OrderPeriodResource($this->resource->invoice?->period),
            'takes'                    => $order?->orderTakes ? TakeInvoiceResource::collection($order->orderTakes) : null,
            'callCenterComment'        => $order?->lastWaitListMessage?->comment,
            'hasAdditionalServices'    => (bool)$this->resource->dopInvoice?->additionalServiceValues,
            'waitList'                 => new WaitListStatusResource($this->resource?->order?->waitListStatuses?->first()),
            'waitListStatusHistory'    => WaitListStatusResource::collection($this->resource->order->waitListStatuses),
        ];
    }
}
