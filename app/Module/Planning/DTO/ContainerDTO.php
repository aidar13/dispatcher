<?php

declare(strict_types=1);

namespace App\Module\Planning\DTO;

use App\Module\Courier\Models\Courier;
use App\Module\Order\Models\Invoice;
use App\Module\Order\Resources\ContainerInvoiceResource;
use App\Module\Planning\Models\ContainerStatus;
use Illuminate\Support\Collection;

final class ContainerDTO
{
    public int $id;
    public string $title;
    public int $invoicesCount;
    public int $stopsCount;
    public Collection $invoices;
    public ContainerStatus $status;
    public int $places = 0;
    public float $weight = 0;
    public float $volumeWeight = 0;
    public ?Courier $courier;
    public ?string $fastDeliveryPrice;
    public ?string $fastDeliveryStatus;
    public ?string $fastDeliveryCourier;
    public ?string $fastDeliveryPhone;
    public ?string $trackingUrl;
    public ?int $fastDeliveryId;
    public ?int $fastDeliveryType;

    public function __construct()
    {
        $this->invoices = collect();
    }

    public function setInvoices(?Collection $invoicesCollection): void
    {
        $lastCoordinate = null;
        $invoices = [];

        /** @var Invoice $invoice */
        foreach ($invoicesCollection as $invoice) {
            $invoices[] = new ContainerInvoiceResource($invoice, $lastCoordinate);

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
