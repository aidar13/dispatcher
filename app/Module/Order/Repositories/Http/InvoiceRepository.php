<?php

declare(strict_types=1);

namespace App\Module\Order\Repositories\Http;

use App\Module\Gateway\Contracts\Integration\HttpClientRequest;
use App\Module\Order\Contracts\Repositories\Integration\UpdateInvoiceSectorsRepository;
use App\Module\Order\Models\Invoice;
use Illuminate\Support\Facades\Log;

final readonly class InvoiceRepository implements UpdateInvoiceSectorsRepository
{
    public function __construct(private HttpClientRequest $client)
    {
    }

    /**
     * @param Invoice $invoice
     * @return void
     */
    public function update(Invoice $invoice): void
    {
        $path = "/cabinet/api/admin/invoices/$invoice->id/update-sectors";

        $data = [
            'senderDispatcherSectorId'   => $invoice->order->sender?->dispatcher_sector_id,
            'senderSectorId'             => $invoice->order->sender?->sector_id,
            'receiverDispatcherSectorId' => $invoice->receiver?->dispatcher_sector_id,
            'receiverSectorId'           => $invoice->receiver?->sector_id,
        ];

        $response = $this->client->makeRequest(
            'PUT',
            $path,
            $data
        );

        Log::info('Присвоение сектора для накладной в кабинете c ID: ' . $invoice->id, [
            'response' => $response->status(),
            'data'     => $data,
        ]);

        if ($response->failed()) {
            throw new \DomainException('Не удалось присвоить сектор накладной в кабинете: ' . $response->body());
        }
    }
}
