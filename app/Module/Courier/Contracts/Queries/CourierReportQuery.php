<?php

declare(strict_types=1);

namespace App\Module\Courier\Contracts\Queries;

use App\Module\Courier\DTO\CourierReportDTO;
use App\Module\Courier\Models\Courier;
use App\Module\Order\Models\Invoice;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface CourierReportQuery
{
    public function getCourierEndOfDayPaginated(CourierReportDTO $DTO): LengthAwarePaginator;

    public function getCourierEndOfDay(int $courierId, string $date): Courier;

    public function getCloseDayReportCourierTakesAndDeliveries(int $courierId, string $date): Collection|Invoice;
}
