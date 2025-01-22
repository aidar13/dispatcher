<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Resources\Delivery;

use App\Http\Resources\BaseJsonResource;
use App\Module\Company\Resources\CompanyResource;
use App\Module\CourierApp\Resources\CourierPayment\CourierPaymentResource;
use App\Module\Delivery\Models\Delivery;
use App\Module\Order\Models\AdditionalServiceType;
use App\Module\Status\Resources\WaitListStatusResource;

/**
 * @OA\Schema(
 *     @OA\Property(property="id", type="integer", description="ID доставки", example=1),
 *     @OA\Property(property="invoiceId", type="integer", description="ID накладной", example=1),
 *     @OA\Property(property="verify", type="integer", description="ID верификации", example=1),
 *     @OA\Property(property="verifyInvoiceNumber", type="string", description="Накладная для верификации", example="SP1703"),
 *     @OA\Property(property="states", type="bool", description="Я приехал", example="true"),
 *     @OA\Property(property="invoiceNumber", type="string", description="Номер накладной", example="SP012031234"),
 *     @OA\Property(property="payerCompanyName", type="string", description="Имя компании плательщика", example="TOO Azat"),
 *     @OA\Property(property="weight", type="integer", description="Физический вес", example=1),
 *     @OA\Property(property="places", type="integer", description="Места", example=2),
 *     @OA\Property(property="annotation", type="string", description="Примечание к отправке", example="Что-то там"),
 *     @OA\Property(property="codPayment", type="integer", description="Наложенный платеж", example=1),
 *     @OA\Property(property="cashSum", type="number", format="float", description="Сумма наличными", example=2500.75),
 *     @OA\Property(property="paymentMethod", type="integer", description="Вид оплаты", example=1),
 *     @OA\Property(property="sizeType", type="string", description="Размер коробки", example="M"),
 *     @OA\Property(property="shouldReturnDocument", type="boolean", description="Должен вернуть документ", example=true),
 *     @OA\Property(property="paymentTypeId", type="integer", description="Тип оплаты", example=1),
 *     @OA\Property(property="paymentTypeTitle", type="string", description="Название типа оплаты", example="Оплата наличными"),
 *     @OA\Property(property="deliveryTime", type="string", format="date-time", description="Время доставки", example="2024-01-08 12:00:00"),
 *     @OA\Property(property="canGenerateQr", type="bool", description="Можно ли сгенерировать qr"),
 *     @OA\Property(property="checks", type="object", ref="#/components/schemas/CourierPaymentResource"),
 *     @OA\Property(property="hasRiseToTheFloor", type="bool", description="Подъем на этаж"),
 *     @OA\Property(property="dopInvoiceNumber", type="string", example="400274283444000"),
 *     @OA\Property(property="nearTakeInfoIds", type="array", description="IDs заборов рядом с доставкой",
 *         @OA\Items(type="integer", enum={1,2,3,4,5,6,7,8,9}, example=1)),
 *     @OA\Property(property="receiver", ref="#/components/schemas/CourierDeliveryReceiverResource"),
 *     @OA\Property(property="company", ref="#/components/schemas/CompanyResource"),
 *     @OA\Property(property="waitList", ref="#/components/schemas/WaitListStatusResource"),
 * )
 *
 * @property Delivery $resource
 */
final class CourierDeliveryShowResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        $invoice = $this->resource->invoice;

        return [
            'id'                   => $this->resource->id,
            'invoiceId'            => $this->resource->invoice_id,
            'invoiceNumber'        => $this->resource->invoice_number,
            'dopInvoiceNumber'     => $invoice?->dop_invoice_number,
            'weight'               => $this->resource->weight,
            'places'               => $this->resource->places,
            'annotation'           => $invoice?->cargo?->annotation,
            'payerCompanyName'     => $invoice?->payerCompany?->getName(),
            'codPayment'           => $invoice?->cargo?->cod_payment,
            'cashSum'              => $invoice?->cash_sum,
            'paymentMethod'        => $invoice?->payment_method,
            'sizeType'             => $invoice?->cargo?->size_type,
            'shouldReturnDocument' => $invoice?->should_return_document,
            'paymentTypeId'        => $invoice?->payment_type,
            'paymentTypeTitle'     => $invoice?->getPaymentTypeTitle(),
            'deliveryTime'         => $invoice?->sla_date,
            'verify'               => $invoice?->shouldBeVerified(),
            'canGenerateQr'        => $invoice?->canGenerateReceiverQr(),
            'verifyInvoiceNumber'  => $invoice?->getVerifyInvoiceNumber(),
            'nearTakeInfoIds'      => $this->resource->getNearTakeInfoIds(),
            'states'               => $this->resource->hasState(),
            'checks'               => CourierPaymentResource::collection($invoice?->courierPayments ?? collect()),
            'hasRiseToTheFloor'    => $invoice?->hasAdditionalServiceValueByTypeId(AdditionalServiceType::ID_RISE_TO_THE_FLOOR),
            'receiver'             => new CourierDeliveryReceiverResource($invoice?->receiver),
            'company'              => new CompanyResource($this->resource->company),
            'waitList'             => new WaitListStatusResource($this->resource->invoice?->waitListStatuses?->last()),
        ];
    }
}
