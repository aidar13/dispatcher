<?php

declare(strict_types=1);

namespace App\Module\Planning\Handlers;

use App\Helpers\GeoCoordinateHelper;
use App\Module\DispatcherSector\Contracts\Queries\SectorQuery;
use App\Module\Order\Models\InvoiceCargo;
use App\Module\Planning\Commands\CreateContainerCommand;
use App\Module\Planning\Commands\GenerateSectorContainersCommand;
use App\Module\Planning\Contracts\Queries\ContainerQuery;
use App\Module\Planning\Contracts\Queries\PlanningQuery;
use App\Module\Planning\DTO\CreateContainerDTO;
use App\Module\Planning\DTO\SectorInvoiceDTO;
use Illuminate\Bus\Dispatcher;
use Illuminate\Database\Eloquent\Collection;

final class GenerateSectorContainersHandler
{
    private Collection $invoices;

    public function __construct(
        private readonly Dispatcher $dispatcher,
        private readonly PlanningQuery $planningQuery,
        private readonly SectorQuery $sectorQuery,
        private readonly ContainerQuery $containerQuery
    ) {
    }

    public function handle(GenerateSectorContainersCommand $command): void
    {
        $sector = $this->sectorQuery->getById($command->sectorId);

        if ($this->containerQuery->checkSectorHasContainer($sector->id, $command->waveId, $command->date)) {
            return;
        }

        $this->invoices = $this->planningQuery->getSectorInvoices(
            new SectorInvoiceDTO($sector, $command->waveId, $command->date, $command->statusId)
        );

        $this->generateContainers($sector->id, $command->waveId, $command->date, InvoiceCargo::TYPE_SMALL_CARGO, $command->userId);
        $this->generateContainers($sector->id, $command->waveId, $command->date, InvoiceCargo::TYPE_OVERSIZE_CARGO, $command->userId);
    }

    private function generateContainers(int $sectorId, int $waveId, string $date, int $cargoType, int $userId): void
    {
        $invoices = $this->invoices->where('cargo_type', $cargoType);

        if ($invoices->isEmpty()) {
            return;
        }

        $sortedInvoices    = GeoCoordinateHelper::sortByDistance($invoices);
        $groupedInvoiceIds = $sortedInvoices->chunk(10);

        $dto = new CreateContainerDTO();
        $dto->setDate($date);
        $dto->setWaveId($waveId);
        $dto->setSectorId($sectorId);
        $dto->setCargoType($cargoType);

        foreach ($groupedInvoiceIds as $invoiceIds) {
            $dto->setInvoiceIds($invoiceIds);

            $this->dispatcher->dispatchSync(new CreateContainerCommand($userId, $dto));
        }
    }
}
