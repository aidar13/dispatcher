<?php

declare(strict_types=1);

namespace App\Module\Delivery\Services;

use App\Module\Delivery\Contracts\Queries\RouteSheetQuery;
use App\Module\Delivery\Contracts\Services\RouteSheetService as RouteSheetServiceContract;
use App\Module\Delivery\DTO\RouteSheetIndexDTO;
use App\Module\Delivery\Models\RouteSheet;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class RouteSheetService implements RouteSheetServiceContract
{
    public function __construct(
        private readonly RouteSheetQuery $query,
    ) {
    }

    public function getAllPaginated(RouteSheetIndexDTO $DTO): LengthAwarePaginator
    {
        return $this->query->getAllPaginated($DTO);
    }

    public function getWithInfosById(int $id): RouteSheet
    {
        return $this->query->getWithInfosById($id);
    }
}
