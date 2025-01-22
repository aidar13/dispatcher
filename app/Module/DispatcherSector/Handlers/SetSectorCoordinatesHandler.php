<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Handlers;

use App\Module\DispatcherSector\Commands\SetSectorCoordinatesCommand;
use App\Module\DispatcherSector\Contracts\Queries\SectorQuery;
use App\Module\DispatcherSector\Contracts\Repositories\UpdateSectorRepository;
use App\Module\DispatcherSector\DTO\WarehouseDTO;
use App\Module\DispatcherSector\Queries\Http\WarehouseQuery;
use Illuminate\Support\Facades\Log;

final class SetSectorCoordinatesHandler
{
    public function __construct(
        private readonly SectorQuery $query,
        private readonly WarehouseQuery $warehouseQuery,
        private readonly UpdateSectorRepository $repository,
    ) {
    }

    public function handle(SetSectorCoordinatesCommand $command): void
    {
        $sector = $this->query->getById($command->id);

        $warehouse = $this->getWarehouse($sector->dispatcherSector->city_id);

        if (!$warehouse?->latitude || !$warehouse?->longitude) {
            return;
        }

        $sector->latitude  = $warehouse->latitude;
        $sector->longitude = $warehouse->longitude;

        $this->repository->update($sector);
    }

    private function getWarehouse(int $cityId): ?WarehouseDTO
    {
        try {
            return $this->warehouseQuery->getByCityId($cityId);
        } catch (\Exception $exception) {
            Log::info('Ошибка при получении склада по городу', [
                'cityId'    => $cityId,
                'exception' => $exception
            ]);

            return null;
        }
    }
}
