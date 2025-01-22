<?php

declare(strict_types=1);

namespace App\Module\Planning\DTO;

use App\Module\Order\Models\Invoice;
use App\Module\Order\Resources\PlanningInvoiceResource;
use App\Module\Planning\Models\Container;
use App\Module\Planning\Models\ContainerStatus;
use Illuminate\Support\Collection;

final class PlanningDTO
{
    public int $id;
    public string $name;
    public string $date;
    public ?string $timeFrom;
    public ?string $timeTo;
    public int $invoicesCount;
    public int $stopsCount;
    public Collection $invoices;
    public Collection $containers;
    public ContainerStatus $status;
    public int $places = 0;
    public float $weight = 0;
    public float $volumeWeight = 0;

    public function __construct()
    {
        $this->invoices   = collect();
        $this->containers = collect();
    }

    public function setInvoices(?Collection $invoicesCollection, Collection $containers): void
    {
        $lastCoordinate = null;
        $invoices       = [];

        /** @var Invoice $invoice */
        foreach ($invoicesCollection as $invoice) {
            $invoices[] = new PlanningInvoiceResource($invoice, $lastCoordinate);

            $this->places       += (int)$invoice?->cargo?->places;
            $this->weight       += (float)$invoice?->cargo?->weight;
            $this->volumeWeight += (float)$invoice?->cargo?->volume_weight;

            $lastCoordinate = $invoice->getReceiverCoordinate();
        }

        $this->places       += $containers->sum('places');
        $this->weight       += $containers->sum('weight');
        $this->volumeWeight += $containers->sum('volumeWeight');

        $this->invoices = collect($invoices);
    }

    public function setContainers(?Collection $containers): void
    {
        /** @var Container $container */
        foreach ($containers as $container) {
            $containerDTO                      = new ContainerDTO();
            $containerDTO->id                  = $container->id;
            $containerDTO->title               = $container->title;
            $containerDTO->invoicesCount       = $container->invoices->count();
            $containerDTO->status              = $container->status;
            $containerDTO->courier             = $container->courier;
            $containerDTO->fastDeliveryPrice   = $container->fastDeliveryOrder?->price;
            $containerDTO->fastDeliveryStatus  = $container->fastDeliveryOrder?->internal_status;
            $containerDTO->fastDeliveryCourier = $container->fastDeliveryOrder?->getCourier();
            $containerDTO->fastDeliveryPhone   = $container->fastDeliveryOrder?->courier_phone;
            $containerDTO->trackingUrl         = $container->fastDeliveryOrder?->tracking_url;
            $containerDTO->fastDeliveryId      = $container->fastDeliveryOrder?->internal_id;
            $containerDTO->fastDeliveryType    = $container->fastDeliveryOrder?->type;
            $containerDTO->setInvoices($container->invoices);
            $containerDTO->setStopsCount($container->invoices);

            $this->containers->push($containerDTO);
        }
    }

    public function setStopsCount(?Collection $invoices, int $containerStops): void
    {
        $this->stopsCount = $containerStops + $invoices
                ->map(function (Invoice $item) {
                    return $item->receiver?->latitude . '-' . $item->receiver?->longitude;
                })->unique()
                ->count();
    }
}
