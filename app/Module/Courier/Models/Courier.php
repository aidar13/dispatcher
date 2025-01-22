<?php

declare(strict_types=1);

namespace App\Module\Courier\Models;

use App\Helpers\NumberHelper;
use App\Models\User;
use App\Module\Car\Models\Car;
use App\Module\Car\Models\CarOccupancy;
use App\Module\Car\Models\CarOccupancyType;
use App\Module\Company\Models\Company;
use App\Module\CourierApp\Models\CourierPayment;
use App\Module\CourierApp\Models\CourierState;
use App\Module\CourierApp\Models\CourierStop;
use App\Module\Delivery\Models\Delivery;
use App\Module\DispatcherSector\Models\DispatcherSector;
use App\Module\DispatcherSector\Models\Sector;
use App\Module\File\Models\File;
use App\Module\Planning\Models\Container;
use App\Module\Planning\Models\ContainerInvoice;
use App\Module\Status\Models\OrderStatus;
use App\Module\Status\Models\StatusType;
use App\Module\Take\Models\OrderTake;
use Database\Factories\CourierFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int|null $company_id
 * @property string $full_name
 * @property string $phone_number
 * @property string $password
 * @property int $dispatcher_sector_id
 * @property int $type
 * @property int $status_id
 * @property int $user_id
 * @property int $car_id
 * @property bool $is_active
 * @property bool $routing_enabled
 * @property string $iin
 * @property string $code_1c
 * @property int|null $schedule_type_id
 * @property int|null $payment_rate_type
 * @property float|null $payment_amount
 * @property Carbon|null $updated_at
 * @property Carbon|null $created_at
 * @property Carbon|null $deleted_at
 * @property int $stops_count
 * @property-read CourierStatus $status
 * @property-read CourierScheduleType|null $scheduleType
 * @property-read DispatcherSector|null $dispatcherSector
 * @property-read Company|null $company
 * @property-read Car|null $car
 * @property-read User $user
 * @property-read Collection|OrderTake[] $takes
 * @property-read Collection|Delivery[] $deliveries
 * @property-read Collection|Container[] $containers
 * @property-read Collection|CourierSchedule[] $schedules
 * @property-read Collection|Sector[] $containerSectors
 * @property-read Collection|ContainerInvoice[] $containerInvoices
 * @property-read Collection|CarOccupancyType[] $carOccupancyTypes
 * @property-read Collection|CarOccupancy[] $carOccupancies
 * @property-read CarOccupancy $carOccupancy
 * @property-read Collection|CourierStop[] $stops
 * @property-read Collection|CloseCourierDay[] $closeCourierDays
 * @property-read Collection|CourierPayment[] $courierPayments
 * @property-read File $files
 * @property-read File|null $identificationCard
 * @property-read File|null $driverLicense
 * @property-read CourierState[]|Collection $courierStates
 * @property-read CourierSector[]|Collection $courierSectors
 * @property Collection|object $info
 * @property int $takes_count
 * @property int $deliveries_count
 * @property-read CourierLicense|null $license
 */
final class Courier extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const DOCUMENT_PATH           = 'courier_documents';
    public const CHECKS_BUCKET_NAME      = 'courier_checks';
    public const SHORTCOMING_BUCKET_NAME = 'shortcoming_courier_files';

    protected static function newFactory(): CourierFactory
    {
        return CourierFactory::new();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(CourierStatus::class, 'status_id');
    }

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class, 'car_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function dispatcherSector(): BelongsTo
    {
        return $this->belongsTo(DispatcherSector::class, 'dispatcher_sector_id');
    }

    public function scheduleType(): BelongsTo
    {
        return $this->belongsTo(CourierScheduleType::class, 'schedule_type_id');
    }

    public function takes(): HasMany
    {
        return $this->hasMany(OrderTake::class, 'courier_id');
    }

    public function deliveries(): HasMany
    {
        return $this->hasMany(Delivery::class, 'courier_id');
    }

    public function containers(): HasMany
    {
        return $this->hasMany(Container::class, 'courier_id');
    }

    public function containerSectors(): HasManyThrough
    {
        return $this->hasManyThrough(Sector::class, Container::class, 'courier_id', 'id', 'id', 'sector_id');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(CourierSchedule::class, 'courier_id');
    }

    public function containerInvoices(): HasManyThrough
    {
        return $this->hasManyThrough(ContainerInvoice::class, Container::class, 'courier_id', 'container_id', 'id', 'id')
            ->with(['invoice.cargo']);
    }

    public function getFullness(): string
    {
        $cubature = $this->car?->cubature ?? 1;

        return ($this->getContainerInvoicesCubature() / $cubature) * 100 . '%';
    }

    public function getContainerInvoicesCubature(): float
    {
        return NumberHelper::getRounded($this->containerInvoices->sum(
            fn(ContainerInvoice $item) => $item->invoice?->cargo?->width * $item->invoice?->cargo?->height * $item->invoice?->cargo?->depth / 1000000
        ), 5);
    }

    public function getLeftTakesCount(Collection $takes): int
    {
        return $takes->where('status_id', '!=', StatusType::ID_CARGO_HANDLING)->count();
    }

    public function getDeliveriesCount(Collection $deliveries): int
    {
        return $deliveries->where('status_id', '!=', StatusType::ID_DELIVERED)->count();
    }

    public function carOccupancies(): HasMany
    {
        return $this->hasMany(CarOccupancy::class, 'user_id', 'user_id');
    }

    public function carOccupancy(): HasOne
    {
        return $this->hasOne(CarOccupancy::class, 'user_id', 'user_id')
            ->orderByDesc('created_at');
    }

    public function stops(): HasMany
    {
        return $this->hasMany(CourierStop::class);
    }

    public function closeCourierDays(): HasMany
    {
        return $this->hasMany(CloseCourierDay::class);
    }

    public function courierPayments(): HasMany
    {
        return $this->hasMany(CourierPayment::class);
    }

    public function carOccupancyTypes(): HasManyThrough
    {
        return $this->hasManyThrough(
            CarOccupancyType::class,
            CarOccupancy::class,
            'user_id',
            'id',
            'user_id',
            'car_occupancy_type_id'
        )->where('type_id', CarOccupancy::COURIER_WORK_TYPE_ID_TAKE);
    }

    public function files(): MorphMany
    {
        return $this->morphMany(File::class, 'client');
    }

    public function identificationCard(): MorphMany
    {
        return $this->files()->where('type', File::TYPE_COURIER_IDENTIFICATION_CARD)->latest();
    }

    public function driverLicense(): MorphMany
    {
        return $this->files()->where('type', File::TYPE_COURIER_DRIVER_LICENSE)->latest();
    }

    public function courierStates(): HasMany
    {
        return $this->hasMany(CourierState::class, 'courier_id', 'id');
    }

    public function courierSectors(): HasMany
    {
        return $this->hasMany(CourierSector::class, 'courier_id', 'id');
    }

    public function getAllowedCourierSectors(): Collection
    {
        return $this->courierSectors()
            ->where('type', CourierSector::TYPE_ALLOWED)
            ->get();
    }

    public function license(): HasOne
    {
        return $this->hasOne(CourierLicense::class, 'courier_id', 'id');
    }

    public function isStatusActive(): bool
    {
        return $this->status_id === CourierStatus::ID_ACTIVE;
    }

    public function routingEnabled(): bool
    {
        return (bool)$this->routing_enabled;
    }

    /**
     * @psalm-suppress InvalidArgument
     */
    public function getTakenInfosInvoiceCargos(): \Illuminate\Support\Collection
    {
        $filteredTakes = collect();

        /** @var OrderTake $take */
        foreach ($this->takes as $take) {
            if ($take?->invoice?->cargo) {
                $filteredTakes->push($take->invoice->cargo);
            }
        }

        return $filteredTakes;
    }

    public function getDeliveryInvoiceCargos(): \Illuminate\Support\Collection
    {
        $deliveries = $this->deliveries;

        return $deliveries->map(function ($orderStatuses) {
            /** @var OrderStatus $orderStatuses */
            return $orderStatuses->invoice?->cargo;
        });
    }
}
