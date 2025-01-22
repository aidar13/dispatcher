<?php

declare(strict_types=1);

namespace App\Module\Planning\Services;

use App\Helpers\DateHelper;
use App\Module\Planning\Contracts\Queries\PlanningQuery;
use App\Module\Planning\Contracts\Services\PlanningService as PlanningServiceContract;
use App\Module\Planning\DTO\PlanningShowDTO;
use App\Module\Planning\DTO\PlanningDTO;
use App\Module\DispatcherSector\Contracts\Queries\WaveQuery;
use App\Module\DispatcherSector\Models\Sector;
use Carbon\Carbon;
use Illuminate\Support\Collection;

final class PlanningService implements PlanningServiceContract
{
    protected Collection $sectors;

    public function __construct(
        private readonly WaveQuery $waveQuery,
        private readonly PlanningQuery $planningQuery
    ) {
        $this->sectors = collect();
    }

    public function getSectors(PlanningShowDTO $DTO): Collection
    {
        $wave    = $this->waveQuery->getById($DTO->waveId);
        $sectors = $this->planningQuery->getSectors($DTO);

        $timeFrom = DateHelper::getTime(Carbon::make($wave->from_time));
        $timeTo   = DateHelper::getTime(Carbon::make($wave->to_time));

        /** @var Sector $sector */
        foreach ($sectors as $sector) {
            $dto           = new PlanningDTO();
            $dto->id       = $sector->id;
            $dto->name     = $sector->name;
            $dto->timeFrom = $timeFrom;
            $dto->timeTo   = $timeTo;
            $dto->setContainers($sector->containers);
            $dto->setInvoices($sector->invoices, $dto->containers);
            $dto->setStopsCount($sector->invoices, $dto->containers->sum('stopsCount'));
            $dto->invoicesCount = $sector->invoices->count() + (int)$dto->containers->sum('invoicesCount');

            $this->sectors->push($dto);
        }

        return $this->sectors;
    }
}
