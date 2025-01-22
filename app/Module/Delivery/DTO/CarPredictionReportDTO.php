<?php

declare(strict_types=1);

namespace App\Module\Delivery\DTO;

use App\Module\DispatcherSector\Models\Sector;
use App\Module\DispatcherSector\Models\Wave;
use App\Module\Order\Models\Invoice;
use Illuminate\Support\Collection;

final class CarPredictionReportDTO
{
    public int $dispatcherSectorId;
    public string $date;
    public Collection $cars;
    public CarPredictionDetailDTO $truckDetail;
    public CarPredictionDetailDTO $passangerDetail;

    public function __construct()
    {
        $this->truckDetail     = new CarPredictionDetailDTO();
        $this->passangerDetail = new CarPredictionDetailDTO();
    }

    public function setCars(?Collection $invoices): void
    {
        $cars = [];

        $groupedByWave = $invoices->groupBy('wave_id');

        /** @var Collection|Invoice[] $waveInvoices */
        foreach ($groupedByWave as $waveInvoices) {
            /** @var Wave $wave */
            $wave = $waveInvoices->first()->wave;

            $groupedBySector = $waveInvoices->groupBy('receiver.sector_id');

            /** @var Collection|Invoice[] $sectorInvoices */
            foreach ($groupedBySector as $sectorInvoices) {
                /** @var Sector $sector */
                $sector = $sectorInvoices->first()?->receiver?->sector;

                $dto             = new CarPredictionItemDTO($sectorInvoices);
                $dto->waveTitle  = $wave?->title;
                $dto->sectorName = $sector?->name;
                $dto->waveId     = $wave?->id;
                $dto->sectorId   = $sector?->id;

                $this->passangerDetail->setData($dto->passanger);
                $this->truckDetail->setData($dto->truck);

                $cars[] = $dto;
            }
        }

        $this->cars = collect($cars);
    }
}
