<?php

declare(strict_types=1);

namespace App\Module\Delivery\Resources;

use App\Helpers\DateHelper;
use App\Http\Resources\BaseJsonResource;
use App\Module\Courier\Resources\CourierShortInfoResource;
use App\Module\Delivery\Models\Delivery;
use App\Module\Status\Models\RefStatus;
use App\Module\Status\Resources\RefStatusResource;
use App\Module\Status\Resources\StatusTypeResource;
use App\Module\Status\Resources\WaitListStatusResource;
use App\Module\Take\Resources\CustomerResource;
use Exception;

/**
 * @OA\Schema(
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="invoiceNumber", type="string", description="Номер Накладной", example="SP00000001"),
 *     @OA\Property(property="companyName", type="string", example="ТОО Spark"),
 *     @OA\Property(property="cityName", type="string", example="Атырау"),
 *     @OA\Property(property="createdAt", type="string", example="2023-01-01 12:12:12"),
 *     @OA\Property(property="orderId", type="int", example=1),
 *     @OA\Property(property="invoiceId", type="int", example=1),
 *     @OA\Property(property="orderNumber", type="string", example="000123213"),
 *     @OA\Property(property="clientAddress", type="string", example="Толеби 101"),
 *     @OA\Property(property="courierName", type="string", example="Азат Абзал"),
 *     @OA\Property(property="deliveredAt", type="string", example="2023-01-01 23:23:23"),
 *     @OA\Property(property="changedTakeDateAt", type="string", example="2023-01-01 23:23:23"),
 *     @OA\Property(property="latitude", type="string", example="78.183604"),
 *     @OA\Property(property="longitude", type="string", example="69.353267"),
 *     @OA\Property(property="hasScan", type="boolean", example=true),
 *     @OA\Property(property="containerTitle", type="string", example="testContainer"),
 *     @OA\Property(property="hasRoadCourierPayment", type="bool", example="true"),
 *     @OA\Property(property="hasParkingCourierPayment", type="bool", example="false"),
 *     @OA\Property(property="problems", type="array",
 *         @OA\Items(
 *             type="string",
 *             example="Опаздывает"
 *         )
 *     ),
 *     @OA\Property(
 *         property="courier",
 *         ref="#/components/schemas/CourierShortInfoResource"
 *     ),
 *     @OA\Property(
 *         property="customer",
 *         ref="#/components/schemas/CustomerResource"
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
 *          property="waitList",
 *          ref="#/components/schemas/WaitListStatusResource"
 *      ),
 * )
 *
 * @property Delivery $resource
 */
final class DeliveryResource extends BaseJsonResource
{
    /**
     * @throws Exception
     */
    public function toArray($request): array
    {
        $waitListStatuses = $this->resource->invoice?->waitListStatuses;

        return [
            'id'                       => $this->resource->id,
            'companyName'              => $this->resource->company?->short_name,
            'cityName'                 => $this->resource->city?->name,
            'createdAt'                => DateHelper::getDateWithTime($this->resource->created_at),
            'orderNumber'              => $this->resource->invoice?->order?->number,
            'invoiceNumber'            => $this->resource->invoice?->invoice_number ?? null,
            'invoiceId'                => $this->resource->invoice?->id ?? null,
            'orderId'                  => $this->resource->invoice?->order?->id ?? null,
            'deliveredAt'              => $this->resource->delivered_at,
            'changedTakeDateAt'        => DateHelper::getDateWithTime($this->resource->invoice?->getStatusByCode(RefStatus::CODE_COURIER_RETURN_DELIVERY)?->created_at),
            'latitude'                 => $this->resource->customer?->latitude,
            'longitude'                => $this->resource->customer?->longitude,
            'hasScan'                  => (bool)$this->resource->invoice?->scan,
            'containerTitle'           => $this->resource->container?->title,
            'problems'                 => $this->resource->invoice?->getProblems(),
            'hasRoadCourierPayment'    => $this->resource->invoice?->courierPaymentsForRoad()?->isNotEmpty(),
            'hasParkingCourierPayment' => $this->resource->invoice?->courierPaymentsForParking()?->isNotEmpty(),
            'courier'                  => new CourierShortInfoResource($this->resource->courier),
            'customer'                 => new CustomerResource($this->resource->customer),
            'status'                   => new StatusTypeResource($this->resource->status),
            'waitListStatus'           => new RefStatusResource($this->resource->refStatus),
            'waitList'                 => $waitListStatuses?->first() ? new WaitListStatusResource($waitListStatuses->first()) : null,
            'waitListStatusesHistory'  => $waitListStatuses?->isNotEmpty() ? WaitListStatusResource::collection($waitListStatuses) : null,
        ];
    }
}
