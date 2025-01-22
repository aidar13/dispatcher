<?php

declare(strict_types=1);

namespace App\Module\Planning\Contracts\Queries;

use App\Module\Planning\DTO\ContainerPaginationDTO;
use App\Module\Planning\DTO\ContainerShowDTO;
use App\Module\Planning\DTO\SendToAssemblyDTO;
use App\Module\Planning\Models\Container;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ContainerQuery
{
    public function getContainersPaginated(ContainerPaginationDTO $DTO): LengthAwarePaginator;

    public function getById(int $id, array $columns = ['*'], array $relations = []): Container;

    public function getAllByIds(array $ids, array $columns = ['*'], array $relations = []): Collection;

    public function checkSectorHasContainer(int $sectorId, int $waveId, string $date): bool;

    public function getLastId(): ?Container;

    public function getAllContainers(ContainerShowDTO $DTO): Collection;

    public function getAllContainersToAssembly(SendToAssemblyDTO $DTO): Collection;

    public function getFastDeliveryByContainers(array $containerIds): Collection;

    public function getByCourierIdForRouting(int $courierId): ?Container;
}
