<?php

declare(strict_types=1);

namespace App\Module\Delivery\DTO;

use App\Helpers\NumberHelper;
use App\Module\Car\Models\Car;
use App\Module\Order\Models\Invoice;
use App\Module\Order\Models\InvoiceCargo;
use Illuminate\Support\Collection;

final class CarPredictionDTO
{
    public int $invoicesCount = 0;
    public int $carCount = 0;
    public int $stopsCount = 0;
    public float $weight = 0;
    public float $volumeWeight = 0;

    public function __construct(Collection $invoices, int $cargoType)
    {
        $invoices = $invoices->where('cargo_type', $cargoType);

        $this->invoicesCount = $invoices->count();
        $this->stopsCount    = $this->getStopsCount($invoices);
        $this->carCount      = $this->getCount($cargoType);
        $this->weight        = NumberHelper::getRounded($invoices->sum(fn(Invoice $item) => $item->cargo?->weight));
        $this->volumeWeight  = NumberHelper::getRounded($invoices->sum(fn(Invoice $item) => $item->cargo?->volume_weight));
    }

    public function getStopsCount(?Collection $invoices): int
    {
        return $invoices
            ->map(function (Invoice $item) {
                return $item->receiver?->latitude . '-' . $item->receiver?->longitude;
            })->unique()
            ->count();
    }

    public function getCount(int $cargoType): int
    {
        return $cargoType === InvoiceCargo::TYPE_SMALL_CARGO
            ? (int)ceil($this->stopsCount / Car::PASSANGER_MAX_STOPS_AMOUNT)
            : (int)ceil($this->stopsCount / Car::TRUCK_MAX_STOPS_AMOUNT);
    }
}
