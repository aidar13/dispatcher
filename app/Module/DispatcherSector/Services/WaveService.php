<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Services;

use App\Helpers\DateHelper;
use App\Module\DispatcherSector\Contracts\Queries\WaveQuery;
use App\Module\DispatcherSector\Contracts\Services\WaveService as WaveServiceContract;
use App\Module\DispatcherSector\DTO\DispatcherWaveDTO;
use App\Module\DispatcherSector\DTO\WaveShowDTO;
use App\Module\DispatcherSector\Models\Wave;
use App\Module\Order\Contracts\Queries\InvoiceQuery;
use Illuminate\Support\Collection;

final class WaveService implements WaveServiceContract
{
    public function __construct(
        private readonly WaveQuery $waveQuery,
        private readonly InvoiceQuery $invoiceQuery,
    ) {
    }

    public function getAll(WaveShowDTO $DTO): Collection
    {
        return $this->waveQuery->getAllByDispatcherSectorId($DTO->dispatcherSectorId);
    }

    public function getById(int $id): Wave
    {
        return $this->waveQuery->getById($id);
    }

    public function getByIdWithFilter(int $id, WaveShowDTO $DTO): DispatcherWaveDTO
    {
        $wave = $this->waveQuery->getById($id);

        $DTO->setWaveId($id);
        $DTO->setDate($wave->to_time);

        $invoices = $this->invoiceQuery->getWaveInvoices($DTO);

        $dto                     = new DispatcherWaveDTO();
        $dto->id                 = $wave->id;
        $dto->title              = $wave->title;
        $dto->dispatcherSectorId = $wave->dispatcher_sector_id;
        $dto->fromTime           = $wave->from_time;
        $dto->toTime             = $wave->to_time;
        $dto->date               = DateHelper::getDate($DTO->date);
        $dto->invoicesCount      = $invoices->count();
        $dto->setStopsCount($invoices);
        $dto->setInvoices($invoices);
        $dto->setSectors($invoices);

        return $dto;
    }
}
