<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\DTO;

use App\Module\DispatcherSector\Resources\SectorResource;
use App\Module\Order\Models\Invoice;
use App\Module\Order\Resources\InvoiceResource;
use Illuminate\Support\Collection;

final class DispatcherWaveDTO
{
    public int $id;
    public string $title;
    public int $dispatcherSectorId;
    public string $fromTime;
    public string $toTime;
    public string $date;
    public int $invoicesCount;
    public int $stopsCount;
    public Collection $invoices;
    public Collection $sectors;
    public float $weight = 0;
    public float $volumeWeight = 0;

    public function __construct()
    {
        $this->invoices = collect();
        $this->sectors  = collect();
    }

    public function setInvoices(?Collection $invoicesCollection): void
    {
        $lastCoordinate = null;
        $invoices = [];

        /** @var Invoice $invoice */
        foreach ($invoicesCollection as $invoice) {
            $invoices[] = new InvoiceResource($invoice, true, $lastCoordinate);

            $this->weight       += floatval($invoice?->cargo?->weight);
            $this->volumeWeight += floatval($invoice?->cargo?->volume_weight);

            $lastCoordinate = $invoice->getReceiverCoordinate();
        }

        $this->invoices = collect($invoices);
    }

    public function setSectors(?Collection $invoices): void
    {
        $sectorInvoices = $invoices->unique('receiver.sector.id');

        /** @var Invoice $invoice */
        foreach ($sectorInvoices as $invoice) {
            if ($invoice->receiver?->sector) {
                $this->sectors->push(new SectorResource($invoice->receiver?->sector, false));
            }
        }
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
