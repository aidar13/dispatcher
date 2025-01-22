<?php

declare(strict_types=1);

namespace App\Module\Delivery\Resources;

use App\Helpers\DateHelper;
use App\Http\Resources\BaseJsonResource;
use App\Module\Delivery\Models\RouteSheetInvoice;
use App\Module\Status\Models\RefStatus;
use App\Module\Status\Resources\RefStatusResource;
use App\Module\Status\Resources\StatusTypeResource;
use Exception;

/**
 *
 * @OA\Schema(
 *     @OA\Property(property="invoiceId", type="integer", description="Айди накладной",example=1),
 *     @OA\Property(property="deliveredDate", type="string", description="Дата Факт доставки", example="2023-10-13 15:48:00"),
 *     @OA\Property(property="invoiceNumber", type="string", description="Номер накладной", example="SP002"),
 *     @OA\Property(property="courierReturnDate", type="string", description="Возврат выдачи", example="2023-08-08 13:00"),
 *     @OA\Property(property="cityName", type="string", description="Название города", example="Алматы"),
 *     @OA\Property(property="sectorName", type="string", description="Название сектора", example="Сектор1"),
 *     @OA\Property(property="address", type="string", description="Адрес получателя", example="Толеби 101"),
 *     @OA\Property(property="comment", type="string", description="Комментарий", example="Код от домофона 777"),
 *     @OA\Property(property="waveName", type="string", description="Наименование Волны", example="Волна1"),
 *     @OA\Property(property="receiverName", type="string", description="Имя получателя", example="Егор"),
 *     @OA\Property(property="places", type="integer", description="Кол-во мест в накладной", example="100"),
 *     @OA\Property(property="weight", type="integer", description="Кол-во физ веса в накладной", example="100"),
 *     @OA\Property(property="volumeWeight", type="integer", description="Кол-во обьемного веса в накладной", example="100"),
 *     @OA\Property(property="companyName", type="integer", description="Наименование клиента", example="Спарк Логистика"),
 *     @OA\Property(property="customerInvoiceNumber", type="string", description="Номер накладной заказчика", example="SP002"),
 *     @OA\Property(property="codPayment", type="integer", description="Сумма наложенного платежа", example="100"),
 *     @OA\Property(property="cashSum", type="numeric", description="Сумма наличных", example="100.00"),
 *     @OA\Property(property="callCenterComment", type="string", example="Comment"),
 *     @OA\Property(
 *         property="deliveryStatus",
 *         ref="#/components/schemas/StatusTypeResource"
 *     ),
 *     @OA\Property(
 *         property="waitListStatus",
 *         ref="#/components/schemas/RefStatusResource"
 *     ),
 * )
 *
 * @property RouteSheetInvoice $resource
 */

final class RouteSheetInvoicesInfoResource extends BaseJsonResource
{
    /**
     * @throws Exception
     */
    public function toArray($request): array
    {
        $invoice = $this->resource->invoice;

        $lastDelivery = $invoice?->deliveries?->last();

        return [
            'invoiceId'             => $invoice?->id,
            'deliveredDate'         => $lastDelivery?->delivered_at ?: null,
            'invoiceNumber'         => $invoice?->invoice_number,
            'courierReturnDate'     => DateHelper::getDateWithTime($invoice?->statuses->where('code', RefStatus::CODE_COURIER_RETURN_DELIVERY)?->last()?->created_at),
            'cityName'              => $invoice?->receiver?->city?->name,
            'sectorName'            => $invoice?->receiver?->sector?->name,
            'address'               => $invoice?->receiver?->full_address,
            'comment'               => $invoice?->receiver?->comment,
            'waveName'              => $invoice?->wave?->title,
            'receiverName'          => $lastDelivery?->delivery_receiver_name,
            'places'                => $invoice?->cargo?->places,
            'weight'                => $invoice?->cargo?->weight,
            'volumeWeight'          => $invoice?->cargo?->volume_weight,
            'companyName'           => $invoice?->order?->company?->short_name,
            'customerInvoiceNumber' => $invoice?->dop_invoice_number,
            'codPayment'            => $invoice?->cargo?->cod_payment,
            'cashSum'               => $invoice?->cash_sum,
            'deliveryStatus'        => new StatusTypeResource($lastDelivery?->status),
            'waitListStatus'        => new RefStatusResource($lastDelivery?->refStatus),
            'callCenterComment'     => $invoice?->lastWaitListMessage?->comment
        ];
    }
}
