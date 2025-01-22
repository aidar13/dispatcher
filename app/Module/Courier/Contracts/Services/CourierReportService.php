<?php

declare(strict_types=1);

namespace App\Module\Courier\Contracts\Services;

use App\Module\Courier\DTO\CourierReportDTO;
use App\Module\Courier\Models\Courier;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CourierReportService
{
    public function getCourierEndOfDayPaginated(CourierReportDTO $DTO): LengthAwarePaginator;

    public function getCourierEndOfDay(int $courierId, string $date): Courier;
}
