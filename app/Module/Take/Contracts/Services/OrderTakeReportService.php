<?php

declare(strict_types=1);

namespace App\Module\Take\Contracts\Services;

use App\Module\Take\DTO\OrderTakeShowDTO;
use Illuminate\Support\Collection;

interface OrderTakeReportService
{
    public function getForExcel(OrderTakeShowDTO $DTO, array $columns = ['*'], array $relations = []): Collection;
}
