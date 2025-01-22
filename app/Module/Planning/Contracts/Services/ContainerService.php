<?php

declare(strict_types=1);

namespace App\Module\Planning\Contracts\Services;

use App\Module\Planning\DTO\ContainerPaginationDTO;
use App\Module\Planning\DTO\ContainerShowDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ContainerService
{
    public function getContainersPaginated(ContainerPaginationDTO $DTO): LengthAwarePaginator;

    public function getAllContainers(ContainerShowDTO $DTO): Collection;
}
