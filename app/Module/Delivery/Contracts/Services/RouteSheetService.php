<?php

declare(strict_types=1);

namespace App\Module\Delivery\Contracts\Services;

use App\Module\Delivery\DTO\RouteSheetIndexDTO;
use App\Module\Delivery\Models\RouteSheet;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface RouteSheetService
{
    public function getAllPaginated(RouteSheetIndexDTO $DTO): LengthAwarePaginator;

    public function getWithInfosById(int $id): RouteSheet;
}
