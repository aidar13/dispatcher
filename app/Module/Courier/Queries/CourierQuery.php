<?php

declare(strict_types=1);

namespace App\Module\Courier\Queries;

use App\Module\Courier\Contracts\Queries\CourierQuery as CourierQueryContract;
use App\Module\Courier\DTO\CourierExportDTO;
use App\Module\Courier\DTO\CourierShowDTO;
use App\Module\Courier\DTO\CourierTakeListShowDTO;
use App\Module\Courier\Models\Courier;
use App\Module\Courier\Models\CourierScheduleType;
use App\Module\Courier\Models\CourierStatus;
use App\Module\Monitoring\DTO\CourierInfoShowDTO;
use App\Module\Planning\DTO\PlanningCourierShowDTO;
use App\Module\Status\Models\RefStatus;
use App\Module\Status\Models\StatusType;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

final class CourierQuery implements CourierQueryContract
{
    public function getById(int $id): Courier
    {
        /** @var Courier */
        return Courier::query()->findOrFail($id);
    }

    public function getByUserId(int $userId): ?Courier
    {
        /** @var Courier */
        return Courier::query()
            ->where('user_id', $userId)
            ->first();
    }

    public function getByPhone(string $phone): Courier
    {
        /** @var Courier */
        return Courier::query()
            ->where('phone_number', $phone)
            ->firstOrFail();
    }

    public function getByCarNumber(string $carNumber): Courier
    {
        /** @var Courier */
        return Courier::query()
            ->where('routing_enabled', 1)
            ->whereRelation('car', 'number', $carNumber)
            ->firstOrFail();
    }

    public function getAllPaginated(CourierShowDTO $DTO, array $columns = ['*'], array $relations = []): LengthAwarePaginator
    {
        return $this->initQuery($DTO)
            ->select($columns)
            ->with($relations)
            ->orderByDesc('id')
            ->paginate($DTO->limit, ['*'], 'page', $DTO->page);
    }

    public function getAllForExport(CourierExportDTO $DTO): Collection
    {
        /** @var Collection */
        return $this->initQuery($DTO)
            ->select(['id', 'full_name', 'created_at', 'dispatcher_sector_id', 'car_id'])
            ->with([
                'dispatcherSector:id,name',
                'car:id,vehicle_type_id',
                'car.carType:id,volume,capacity',
                'takes' => function ($builder) use ($DTO) {
                    return $builder
                        ->with(['invoice.cargo'])
                        ->whereHas('statuses', function ($query) {
                            return $query->where('code', RefStatus::CODE_CARGO_PICKED_UP);
                        })
                        ->when($DTO->fromDate, fn($query) => $query->where('created_at', '>=', $DTO->fromDate))
                        ->when($DTO->toDate, fn($query) => $query->where('created_at', '<=', $DTO->toDate));
                },
                'deliveries' => function ($builder) use ($DTO) {
                    return $builder
                        ->with(['invoice.cargo'])
                        ->whereNotNull('delivered_at')
                        ->when($DTO->fromDate, fn($query) => $query->where('delivered_at', '>=', $DTO->fromDate))
                        ->when($DTO->toDate, fn($query) => $query->where('delivered_at', '<=', $DTO->toDate));
                },
            ])
            ->withCount(['stops'])
            ->orderByDesc('id')
            ->get();
    }

    private function initQuery(CourierShowDTO|CourierExportDTO $DTO): Builder
    {
        $currentDate = Carbon::now();

        return Courier::query()
            ->when($DTO->iin, fn(Builder $query) => $query->where('iin', 'like', '%' . $DTO->iin . '%'))
            ->when($DTO->name, fn(Builder $query) => $query->where('full_name', 'like', '%' . $DTO->name . '%'))
            ->when($DTO->phoneNumber, fn(Builder $query) => $query->where('phone_number', 'like', '%' . $DTO->phoneNumber . '%'))
            ->when($DTO->companyId, fn(Builder $query) => $query->where('company_id', $DTO->companyId))
            ->when($DTO->statusIds, fn(Builder $query) => $query->whereIn('status_id', $DTO->statusIds))
            ->when($DTO->createdAtFrom, fn(Builder $query) => $query->where('created_at', '>=', $DTO->createdAtFrom))
            ->when($DTO->createdAtUntil, fn(Builder $query) => $query->where('created_at', '<=', $DTO->createdAtUntil))
            ->when($DTO->dispatcherSectorIds, fn(Builder $query) => $query->whereIn('dispatcher_sector_id', $DTO->dispatcherSectorIds))
            ->when($DTO->id, fn(Builder $query) => $query->where('id', $DTO->id))
            ->when($DTO->code1C, fn(Builder $query) => $query->where('code_1c', 'like', '%' . $DTO->code1C . '%'))
            ->when(
                $DTO->carModel || $DTO->carNumber,
                fn(Builder $query) => $query->whereHas('car', function (Builder $query) use ($DTO) {
                    $query->when(
                        $DTO->carNumber,
                        fn($query) => $query->where('number', 'like', '%' . $DTO->carNumber . '%'),
                        fn($query) => $query->where('model', 'like', '%' . $DTO->carModel . '%'),
                    );
                })
            )
            ->when(
                $DTO->shiftId === CourierScheduleType::ID_ON_SHIFT,
                function ($query) use ($currentDate) {
                    $query->whereHas('scheduleType', function ($query) use ($currentDate) {
                        $query->where('work_time_from', '<=', $currentDate)
                            ->where('work_time_until', '>', $currentDate);
                    });
                }
            )
            ->when(
                $DTO->shiftId === CourierScheduleType::ID_OUT_SHIFT,
                function ($query) use ($currentDate) {
                    $query->whereHas('scheduleType', function ($query) use ($currentDate) {
                        $query->where('work_time_from', '>', $currentDate)
                            ->orWhere('work_time_until', '<=', $currentDate);
                    });
                }
            );
    }

    public function getCouriersTakeListPaginated(CourierTakeListShowDTO $DTO): LengthAwarePaginator
    {
        return Courier::query()
            ->select(['id', 'full_name', 'phone_number', 'car_id', 'dispatcher_sector_id', 'user_id'])
            ->with([
                'takes' => function ($query) {
                    return $query
                        ->select(['courier_id','status_id','customer_id','invoice_id'])
                        ->where('status_id', StatusType::ID_ASSIGNED)
                        ->with(['customer' => function ($query) {
                            return $query->select(['id', 'sector_id'])
                                ->with(['sector:id,name']);
                        }]);
                },
                'deliveries' => function ($query) {
                    return $query
                        ->select(['courier_id','status_id','customer_id','invoice_id'])
                        ->whereIn('status_id', [StatusType::ID_IN_DELIVERING, StatusType::ID_DELIVERY_CREATED])
                        ->with(['customer' => function ($query) {
                            return $query->select(['id', 'sector_id'])
                                ->with(['sector:id,name']);
                        }]);
                },
                'dispatcherSector:id,name,city_id',
                'carOccupancy' => fn($q) => $q->whereDate('created_at', now())
                    ->with('carOccupancyType:id,percent,title')
                    ->take(1),
            ])
            ->where(function (Builder $query) {
                $query
                    ->whereHas('takes', function ($query) {
                        return $query->where('status_id', StatusType::ID_ASSIGNED);
                    })
                    ->orWhereHas('deliveries', function ($query) {
                        return $query->whereIn('status_id', [StatusType::ID_IN_DELIVERING, StatusType::ID_DELIVERY_CREATED]);
                    });
            })
            ->when($DTO->scheduleTypeId, fn(Builder $query) => $query->where('schedule_type_id', $DTO->scheduleTypeId))
            ->when($DTO->statusIds, fn(Builder $query) => $query->whereIn('status_id', $DTO->statusIds))
            ->when($DTO->dispatcherSectorId, fn(Builder $query) => $query->whereRelation('dispatcherSector', 'id', $DTO->dispatcherSectorId))
            ->when($DTO->sectorIds, fn(Builder $query) => $query->whereHas(
                'dispatcherSector.sectors',
                function ($query) use ($DTO) {
                    $query->whereIn('id', $DTO->sectorIds);
                }
            ))->paginate($DTO->limit, ['*'], 'page', $DTO->page);
    }

    public function getCouriersByWaveIdAndDate(PlanningCourierShowDTO $DTO): Collection|array
    {
        return Courier::query()
            ->with([
                'schedules',
                'containerInvoices',
                'car',
                'containerSectors' => function ($builder) use ($DTO) {
                    $builder
                        ->distinct()
                        ->where('date', $DTO->date);
                },
            ])
            ->whereIn('status_id', [CourierStatus::ID_ACTIVE, CourierStatus::ID_ARCHIVE])
            ->where('dispatcher_sector_id', $DTO->dispatcherSectorId)
            ->whereHas('containers', function (Builder $query) use ($DTO) {
                $query->where('wave_id', $DTO->waveId);
                $query->where('date', $DTO->date);
            })
            ->orderBy('status_id')
            ->get();
    }

    public function getTakesAndDeliveriesByDispatcherSectorIdAndCreatedAtInterval(CourierInfoShowDTO $DTO): Collection
    {
        /** @var Collection */
        return Courier::query()
            ->select(['couriers.id', 'couriers.full_name'])
            ->whereHas('takes', function (Builder $query) use ($DTO) {
                $query->whereHas('customer', function (Builder $query) use ($DTO) {
                    $query->when($DTO->dispatcherSectorId, fn($q) => $q->where('dispatcher_sector_id', $DTO->dispatcherSectorId));
                })
                    ->whereDate('order_takes.take_date', '>=', $DTO->createdAtFrom)
                    ->whereDate('order_takes.take_date', '<=', $DTO->createdAtTo);
            })
            ->orWhereHas('deliveries', function (Builder $query) use ($DTO) {
                $query->whereDate('deliveries.created_at', '>=', $DTO->createdAtFrom)
                    ->whereDate('deliveries.created_at', '<=', $DTO->createdAtTo)
                    ->whereHas('customer', function (Builder $query) use ($DTO) {
                        $query->when($DTO->dispatcherSectorId, fn($q) => $q->where('dispatcher_sector_id', $DTO->dispatcherSectorId));
                    });
            })
            ->with([
                'takes' => fn($q) => $q
                    ->select(['id', 'courier_id', 'status_id', 'order_id'])
                    ->whereDate('take_date', '>=', $DTO->createdAtFrom)
                    ->whereDate('take_date', '<=', $DTO->createdAtTo)
                    ->whereHas('customer', function (Builder $query) use ($DTO) {
                        $query->when($DTO->dispatcherSectorId, fn($q) => $q->where('dispatcher_sector_id', $DTO->dispatcherSectorId));
                    })
                    ->groupBy('order_id'),
                'deliveries' => fn($q) => $q
                    ->select(['id', 'courier_id', 'status_id'])
                    ->whereDate('created_at', '>=', $DTO->createdAtFrom)
                    ->whereDate('created_at', '<=', $DTO->createdAtTo)
                    ->whereHas('customer', function (Builder $query) use ($DTO) {
                        $query->when($DTO->dispatcherSectorId, fn($q) => $q->where('dispatcher_sector_id', $DTO->dispatcherSectorId));
                    })
            ])
            ->get();
    }
}
