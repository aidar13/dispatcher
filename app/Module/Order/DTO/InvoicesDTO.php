<?php

declare(strict_types=1);

namespace App\Module\Order\DTO;

use App\Module\Order\Models\Invoice;
use App\Module\Order\Resources\InvoiceResource;
use Illuminate\Support\Collection;

final class InvoicesDTO
{
    public int $dispatcherSectorId;
    public string $date;
    public int $places = 0;
    public float $weight = 0;
    public float $volumeWeight = 0;
    public int $invoicesCount = 0;
    public int $stopsCount;
    public Collection $invoices;

    public function __construct()
    {
        $this->invoices = collect();
    }

    public function setInvoices(Collection $invoicesCollection): void
    {
        $lastCoordinate = null;
        $invoices = [];

        /** @var Invoice $invoice */
        foreach ($invoicesCollection as $invoice) {
            $invoices[] = new InvoiceResource($invoice, false, $lastCoordinate);

            $this->places       += (int)$invoice?->cargo?->places;
            $this->weight       += floatval($invoice?->cargo?->weight);
            $this->volumeWeight += floatval($invoice?->cargo?->volume_weight);

            $lastCoordinate = $invoice->getReceiverCoordinate();
        }

        $this->invoices = collect($invoices);
    }

    public function setStopsCount(?Collection $invoices): void
    {
        $this->stopsCount = $invoices
                ->map(function (Invoice $item) {
                    return $item->receiver?->latitude . '-' . $item->receiver?->longitude;
                })->unique()
                ->count();
    }
}
