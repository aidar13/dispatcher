<?php

declare(strict_types=1);

namespace App\Module\Delivery\DTO;

use App\Module\Order\Models\InvoiceCargo;
use Illuminate\Support\Collection;

final class CarPredictionItemDTO
{
    public CarPredictionDTO $passanger;
    public CarPredictionDTO $truck;
    public int $invoicesCount = 0;

    public int $stopsCount = 0;
    public float $weight = 0;
    public float $volumeWeight = 0;
    public string|null|int $waveId;
    public string|null|int $sectorId;
    public string|null $waveTitle;
    public string|null $sectorName;

    public function __construct(Collection $invoices)
    {
        $this->passanger     = new CarPredictionDTO($invoices, InvoiceCargo::TYPE_SMALL_CARGO);
        $this->truck         = new CarPredictionDTO($invoices, InvoiceCargo::TYPE_OVERSIZE_CARGO);
        $this->invoicesCount = $this->truck->invoicesCount + $this->passanger->invoicesCount;
        $this->stopsCount    = $this->truck->stopsCount + $this->passanger->stopsCount;
        $this->weight        = $this->truck->weight + $this->passanger->weight;
        $this->volumeWeight  = $this->truck->volumeWeight + $this->passanger->volumeWeight;
    }
}
