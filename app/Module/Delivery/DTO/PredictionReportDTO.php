<?php

declare(strict_types=1);

namespace App\Module\Delivery\DTO;

use App\Module\Order\Models\Invoice;
use App\Module\Status\Models\RefStatus;
use Illuminate\Support\Collection;

final class PredictionReportDTO
{
    public int $dispatcherSectorId;
    public string $date;
    public int $incomingCount = 0;
    public float $incomingWeight = 0;
    public float $incomingVolumeWeight = 0;
    public int $factCount = 0;
    public float $factWeight = 0;
    public float $factVolumeWeight = 0;

    public function setIncoming(?Collection $invoices): void
    {
        $invoices = $invoices->where('status_id', '=', RefStatus::ID_CARGO_IN_TRANSIT);

        $this->incomingCount        = $invoices->count();
        $this->incomingWeight       = floatval($invoices->sum(fn(Invoice $item) => $item->cargo?->weight));
        $this->incomingVolumeWeight = floatval($invoices->sum(fn(Invoice $item) => $item->cargo?->volume_weight));
    }

    public function setFact(?Collection $invoices): void
    {
        $invoices = $invoices->where('status_id', '!=', RefStatus::ID_CARGO_IN_TRANSIT);

        $this->factCount        = $invoices->count();
        $this->factWeight       = floatval($invoices->sum(fn(Invoice $item) => $item->cargo?->weight));
        $this->factVolumeWeight = floatval($invoices->sum(fn(Invoice $item) => $item->cargo?->volume_weight));
    }
}
