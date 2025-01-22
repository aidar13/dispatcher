<?php

declare(strict_types=1);

namespace App\Module\Courier\Services;

use App\Module\Courier\Contracts\Queries\CourierQuery;
use App\Module\Courier\Contracts\Services\CourierService as CourierServiceContract;
use App\Module\Courier\DTO\CourierShowDTO;
use App\Module\Courier\DTO\CourierTakeListShowDTO;
use App\Module\Courier\Models\Courier;
use App\Module\Planning\DTO\PlanningCourierShowDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

final class CourierService implements CourierServiceContract
{
    public function __construct(
        public CourierQuery $query
    ) {
    }

    public function getCourierById(int $id): Courier
    {
        return $this->query->getById($id);
    }

    public function getAllPaginated(CourierShowDTO $DTO): LengthAwarePaginator
    {
        return $this->query->getAllPaginated(
            $DTO,
            ['id', 'iin', 'full_name', 'phone_number', 'user_id', 'code_1c', 'created_at', 'status_id', 'car_id', 'company_id', 'schedule_type_id', 'dispatcher_sector_id', 'routing_enabled'],
            [
                'status:id,title',
                'car:id,number,model,company_id,created_at',
                'company:id,name,short_name,bin',
                'scheduleType:id,title,work_time_from,work_time_until',
                'dispatcherSector:id,name,city_id',
                'dispatcherSector.dispatcherSectorUsers:id,dispatcher_sector_id,user_id',
                'license:id,courier_id,identify_card_number,identify_card_issue_date,driver_license_number,driver_license_issue_date'
            ]
        );
    }

    public function getCouriersTakeListPaginated(CourierTakeListShowDTO $DTO): LengthAwarePaginator
    {
        return $this->query->getCouriersTakeListPaginated($DTO);
    }

    public function getCouriersByWaveIdAndDate(PlanningCourierShowDTO $DTO): Collection|array
    {
        return $this->query->getCouriersByWaveIdAndDate($DTO);
    }

    public function getCourierByUserId(int $userId): ?Courier
    {
        return $this->query->getByUserId($userId);
    }

    public function getCourierByPhone(string $phone): Courier
    {
        return $this->query->getByPhone($phone);
    }
}
