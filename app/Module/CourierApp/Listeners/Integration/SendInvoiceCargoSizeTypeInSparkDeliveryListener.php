<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Listeners\Integration;

use App\Module\Company\Models\Company;
use App\Module\CourierApp\Events\OrderTake\InvoiceCargoSizeTypeSetEvent;
use App\Module\Gateway\Contracts\Integration\HttpClientRequest;
use App\Module\Order\Contracts\Queries\InvoiceCargoQuery;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Support\Facades\Log;

final class SendInvoiceCargoSizeTypeInSparkDeliveryListener
{
    public function __construct(
        private readonly InvoiceCargoQuery $query,
        private readonly HttpClientRequest $externalClient,
    ) {
    }

    /**
     * @throws HttpClientException
     */
    public function handle(InvoiceCargoSizeTypeSetEvent $event): void
    {
        $invoiceCargo = $this->query->getById(
            $event->invoiceCargoId,
            ['id', 'invoice_id', 'size_type'],
            ['invoice:id,order_id,invoice_number', 'invoice.order:id,company_id'],
        );

        if (!$invoiceCargo) {
            return;
        }

        if ($invoiceCargo->invoice->order?->company_id !== Company::COMPANY_SPARK_DELIVERY_ID) {
            return;
        }

        $path = '/order/api/v1/orders/box-template/update';
        $data = [
            'invoiceNumber' => $invoiceCargo->invoice->invoice_number,
            'sizeType'      => $invoiceCargo->size_type
        ];

        $response = $this->externalClient->makeRequest(
            'PUT',
            $path,
            $data,
        );

        Log::info("Редактирование размера коробки в доставке $invoiceCargo->id", [
            'path'     => $path,
            'data'     => $data,
            'response' => $response->json(),
            'status'   => $response->status(),
        ]);

        if ($response->failed()) {
            throw new HttpClientException("Ошибка при редактировании размера упаковки $invoiceCargo->id: " . $response->body());
        }
    }
}
