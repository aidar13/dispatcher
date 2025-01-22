<?php

declare(strict_types=1);

namespace App\Module\Planning\Services;

use App\Module\Planning\Contracts\Queries\ContainerQuery;
use App\Module\Planning\Contracts\Services\ContainerService as ContainerServiceContract;
use App\Module\Planning\DTO\ContainerPaginationDTO;
use App\Module\Planning\DTO\ContainerShowDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

final class ContainerService implements ContainerServiceContract
{
    public function __construct(
        private readonly ContainerQuery $query,
    ) {
    }

    public function getContainersPaginated(ContainerPaginationDTO $DTO): LengthAwarePaginator
    {
        return $this->query->getContainersPaginated($DTO);
    }

    public function getAllContainers(ContainerShowDTO $DTO): Collection
    {
        return $this->query->getAllContainers($DTO);
    }
}
