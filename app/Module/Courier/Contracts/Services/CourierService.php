<?php

declare(strict_types=1);

namespace App\Module\Courier\Contracts\Services;

use App\Module\Courier\DTO\CourierShowDTO;
use App\Module\Courier\DTO\CourierTakeListShowDTO;
use App\Module\Courier\Models\Courier;
use App\Module\Planning\DTO\PlanningCourierShowDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface CourierService
{
    public function getCourierById(int $id): Courier;

    public function getCourierByUserId(int $userId): ?Courier;

    public function getCourierByPhone(string $phone): Courier;

    public function getAllPaginated(CourierShowDTO $DTO): LengthAwarePaginator;

    public function getCouriersTakeListPaginated(CourierTakeListShowDTO $DTO): LengthAwarePaginator;

    public function getCouriersByWaveIdAndDate(PlanningCourierShowDTO $DTO): Collection|array;
}
