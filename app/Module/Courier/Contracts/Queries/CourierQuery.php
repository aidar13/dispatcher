<?php

declare(strict_types=1);

namespace App\Module\Courier\Contracts\Queries;

use App\Module\Courier\DTO\CourierExportDTO;
use App\Module\Courier\DTO\CourierShowDTO;
use App\Module\Courier\DTO\CourierTakeListShowDTO;
use App\Module\Courier\Models\Courier;
use App\Module\Monitoring\DTO\CourierInfoShowDTO;
use App\Module\Planning\DTO\PlanningCourierShowDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface CourierQuery
{
    public function getById(int $id): Courier;

    public function getByUserId(int $userId): ?Courier;

    public function getByPhone(string $phone): Courier;

    public function getByCarNumber(string $carNumber): Courier;

    public function getAllPaginated(CourierShowDTO $DTO, array $columns = ['*'], array $relations = []): LengthAwarePaginator;

    public function getCouriersTakeListPaginated(CourierTakeListShowDTO $DTO): LengthAwarePaginator;

    public function getCouriersByWaveIdAndDate(PlanningCourierShowDTO $DTO): Collection|array;

    public function getAllForExport(CourierExportDTO $DTO): Collection;

    public function getTakesAndDeliveriesByDispatcherSectorIdAndCreatedAtInterval(CourierInfoShowDTO $DTO): Collection;
}
