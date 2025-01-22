<?php

declare(strict_types=1);

namespace App\Module\Delivery\Contracts\Services;

use App\Module\Delivery\DTO\DeliveryReportDTO;
use App\Module\Delivery\DTO\DeliveryShowDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface DeliveryService
{
    public function getAllPaginated(DeliveryShowDTO $DTO): LengthAwarePaginator;

    public function getForExport(DeliveryReportDTO $DTO): Collection;
}
