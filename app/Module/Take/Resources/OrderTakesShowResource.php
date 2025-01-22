<?php

namespace App\Module\Take\Resources;

use App\Http\Resources\BaseJsonResource;
use App\Module\Courier\Resources\CourierShortInfoResource;
use App\Module\Order\Models\Invoice;
use App\Module\Order\Models\Order;
use App\Module\Order\Resources\AdditionalServiceValuesResource;
use App\Module\Order\Resources\SenderResource;
use App\Module\Status\Contracts\Services\TakeStatusService;
use App\Module\Status\Models\StatusType;
use App\Module\Status\Resources\WaitListStatusResource;
use Illuminate\Contracts\Container\BindingResolutionException;

/**
 * @OA\Schema (
 *     @OA\Property(property="orderId", type="integer", example="1"),
 *     @OA\Property(property="number", type="string", example="00005123", description="номер 1с заказа"),
 *     @OA\Property(property="companyName", type="string", example="ТОО SPARK LOGISTICS"),
 *     @OA\Property(property="totalWeight", type="float", example="4.5"),
 *     @OA\Property(property="totalPlaces", type="ineger", example="6"),
 *     @OA\Property(property="periodTitle", type="string", example="После обеда"),
 *     @OA\Property(property="periodId", type="int", example="1"),
 *     @OA\Property(property="courier", type="object", ref="#/components/schemas/CourierShortInfoResource"),
 *     @OA\Property(property="status", type="string", example="Не назначен на курьера/Назначен на курьера/Забран курьером/Забор отгружен на склад"),
 *     @OA\Property(property="problems", type="array",
 *         @OA\Items(
 *             type="string",
 *             example="Перенос даты забора"
 *         )
 *     ),
 *     @OA\Property(property="statusHistory", type="array",
 *         @OA\Items(
 *             @OA\Property(property="status", type="string", example="Не назначен на курьера"),
 *             @OA\Property(property="date", type="string", example="2021-01-01 00:00:00")
 *         )
 *     )),
 *     @OA\Property(property="states", type="array",
 *         @OA\Items(
 *             @OA\Property(property="title", type="string", example="Я приехал"),
 *             @OA\Property(property="date", type="string", example="2021-01-01 00:00:00")
 *         )
 *     )),
 *     @OA\Property(property="takes", type="array", @OA\Items(ref="#/components/schemas/TakeInvoiceResource")),
 *     @OA\Property(property="waitListStatus", type="string", example="Не успел до 18:00"),
 *     @OA\Property(
 *         property="orderAdditionalServicesValues",
 *         ref="#/components/schemas/AdditionalServiceValuesResource"
 *     ),
 *     @OA\Property(
 *          property="waitList",
 *          ref="#/components/schemas/WaitListStatusResource"
 *     ),
 *     @OA\Property(property="waitListStatusesHistory", type="array",
 *         @OA\Items(ref="#/components/schemas/WaitListStatusResource")
 *     )
 * )
 * @property Order $resource
 */
class OrderTakesShowResource extends BaseJsonResource
{
    /**
     * @throws BindingResolutionException
     */
    public function toArray($request): array
    {
        /** @var TakeStatusService $takeStatusService */
        $takeStatusService = app()->make(TakeStatusService::class);

        $take = $this->resource->take;

        /** @var Invoice $invoice */
        $invoice = $this->resource->invoices->first();

        return [
            'orderId'                       => $this->resource->id,
            'number'                        => $this->resource->number,
            'companyName'                   => $this->resource->company->getName(),
            'companyManager'                => $this->resource->company->manager?->email,
            'problems'                      => $this->resource->getProblems(),
            'sender'                        => new SenderResource($this->resource->sender),
            'totalWeight'                   => $this->resource->orderTakes->where('type_id', null)->sum('weight'),
            'totalPlaces'                   => $this->resource->orderTakes->where('type_id', null)->sum('places'),
            'periodId'                      => $invoice?->period_id,
            'periodTitle'                   => $invoice?->getPeriodTitle(),
            'courier'                       => CourierShortInfoResource::make($take->courier),
            'status'                        => $take->status->title,
            'statusHistory'                 => $takeStatusService->getStatusHistory($take),
            'takes'                         => TakeInvoiceResource::collection($this->resource->orderTakes->where('status_id', '!=', StatusType::ID_TAKE_CANCELED)),
            'orderAdditionalServicesValues' => new AdditionalServiceValuesResource($this->resource->invoices->where('type', true)->first()),
            'waitListStatus'                => $take->waitListStatus?->name,
            'states'                        => $take->getCourierStates(),
            'waitList'                      => new WaitListStatusResource($this->resource->waitListStatuses?->last()),
            'waitListStatusesHistory'       => WaitListStatusResource::collection($this->resource->waitListStatuses),
        ];
    }
}
