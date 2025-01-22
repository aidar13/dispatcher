<?php

declare(strict_types=1);

namespace App\Module\Order\Models;

use App\Helpers\CargoHelper;
use App\Helpers\DateHelper;
use App\Libraries\Codes\PaymentMethodCodes;
use App\Libraries\Codes\PaymentTypeCodes;
use App\Module\Company\Models\Company;
use App\Module\CourierApp\Models\CourierPayment;
use App\Module\Delivery\Models\Delivery;
use App\Module\Delivery\Models\ReturnDelivery;
use App\Module\DispatcherSector\Models\Wave;
use App\Module\File\Models\File;
use App\Module\Notification\Models\WaitListMessage;
use App\Module\Order\Contracts\Services\InvoiceService;
use App\Module\Order\Enums\VerificationTypeEnum;
use App\Module\Planning\Models\Container;
use App\Module\Planning\Models\ContainerInvoice;
use App\Module\Status\Models\OrderStatus;
use App\Module\Status\Models\RefStatus;
use App\Module\Status\Models\WaitListStatus;
use App\Module\Take\Models\OrderPeriod;
use App\Module\Take\Models\OrderTake;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Database\Factories\InvoiceFactory;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $invoice_number
 * @property int $order_id
 * @property int|null $status_id
 * @property int $receiver_id
 * @property int $direction_id
 * @property Carbon|null $sla_date
 * @property int $shipment_id
 * @property int|null $wave_id
 * @property string|null $delivery_date
 * @property int|null $period_id
 * @property string|null $take_time
 * @property string|null $take_date
 * @property string|null $code_1c
 * @property string|null $dop_invoice_number
 * @property float|null $cash_sum
 * @property int|null $should_return_document
 * @property int|null $weekend_delivery
 * @property int $verify
 * @property int|null $type
 * @property int|null $container_id
 * @property int|null $payer_company_id
 * @property int|null $cargo_type
 * @property int|null $payment_type
 * @property int|null $payment_method
 * @property int|null $place_quantity
 * @property int|null $wait_list_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property SupportCollection $problems
 * @property-read Order $order
 * @property-read Company $payerCompany
 * @property-read OrderTake $take
 * @property-read InvoiceCargo $cargo
 * @property-read Receiver $receiver
 * @property-read Wave|null $wave
 * @property-read OrderPeriod|null $period
 * @property-read ShipmentType|null $shipmentType
 * @property-read Collection|OrderTake[] $takes
 * @property-read Collection|OrderStatus[] $statuses
 * @property-read OrderStatus|null $latestStatus
 * @property-read RefStatus|null $waitListStatus
 * @property-read RefStatus $status
 * @property-read Collection|AdditionalServiceValue[] $additionalServiceValues
 * @property-read Collection|Delivery[] $deliveries
 * @property-read Collection|ReturnDelivery[] $returnDeliveries
 * @property-read CourierPayment[]|Collection $courierPayments
 * @property-read WaitListMessage $lastWaitListMessage
 * @property-read bool $hasAdditionalServices
 * @property-read bool $hasTransit
 * @property-read int $position
 * @property-read File|null $scan
 * @property-read File|null $file
 * @property-read Container|null $container
 * @property-read Collection|File[] $files
 * @property-read WaitListStatus[]|Collection $waitListStatuses
 * @property-read WaitListStatus|null $lastWaitListStatus
 */
final class Invoice extends Model
{
    use SoftDeletes;
    use HasFactory;

    // Возвратные накладные имеют этот префикс
    const RETURN_INVOICE_SUFFIXES = ['V', 'R'];

    const STATUS_FACT       = 1;
    const STATUS_DELIVERING = 2;
    const STATUS_FACT_TITLE       = 'Фактические';
    const STATUS_DELIVERING_TITLE = 'Прибывающие';
    const STATUS_LATE_TITLE       = 'Опаздывает';

    const RESERVE_HOURS_TO_PROCESSING = 2;

    protected $table = 'invoices';

    protected static function newFactory(): InvoiceFactory
    {
        return InvoiceFactory::new();
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function waitListStatuses(): MorphMany
    {
        return $this->morphMany(WaitListStatus::class, 'client');
    }
    public function lastWaitListStatus(): MorphOne
    {
        return $this->morphOne(WaitListStatus::class, 'client');
    }

    public function lastWaitListMessage(): HasOne
    {
        return $this->hasOne(WaitListMessage::class, 'number', 'invoice_number')
            ->where('type', WaitListMessage::DELIVERY_TYPE)
            ->latest();
    }

    public function payerCompany(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'payer_company_id');
    }

    public function waitListStatus(): BelongsTo
    {
        return $this->belongsTo(RefStatus::class, 'wait_list_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(RefStatus::class, 'status_id');
    }

    public function deliveries(): HasMany
    {
        return $this->hasMany(Delivery::class, 'invoice_id');
    }

    public function take(): HasOne
    {
        return $this->hasOne(OrderTake::class, 'invoice_id');
    }

    public function cargo(): HasOne
    {
        return $this->hasOne(InvoiceCargo::class, 'invoice_id');
    }

    public function takes(): HasMany
    {
        return $this->hasMany(OrderTake::class, 'invoice_id');
    }

    public function statuses(): HasMany
    {
        return $this->hasMany(OrderStatus::class, 'invoice_id');
    }

    public function isCodeInStatuses(int $code): bool
    {
        return $this->statuses->contains('code', $code);
    }

    public function latestStatus(): HasOne
    {
        return $this->hasOne(OrderStatus::class, 'invoice_id')
            ->latestOfMany();
    }

    public function returnDeliveries(): HasMany
    {
        return $this->hasMany(ReturnDelivery::class, 'invoice_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(Receiver::class, 'receiver_id');
    }

    public function shipmentType(): BelongsTo
    {
        return $this->belongsTo(ShipmentType::class, 'shipment_id');
    }

    public function wave(): BelongsTo
    {
        return $this->belongsTo(Wave::class, 'wave_id');
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(OrderPeriod::class, 'period_id');
    }

    public function getStatusByCode(int $code, bool $sortByDesc = false): ?OrderStatus
    {
        return $this->statuses
            ->where('code', $code)
            ->sortBy(fn($item) => $item->id, 0, $sortByDesc)
            ->first();
    }

    public function isCurrentStatus(int $statusId): bool
    {
        return $this->status_id == $statusId;
    }

    public function isDeliveringToCity(): bool
    {
        return $this->isCurrentStatus(RefStatus::ID_CARGO_IN_TRANSIT);
    }

    public function isInWarehouse(): bool
    {
        return $this->isCurrentStatus(RefStatus::ID_CARGO_AWAIT_SHIPMENT) ||
            $this->isCurrentStatus(RefStatus::ID_CARGO_ARRIVED_CITY);
    }

    public function getDeliveryTime(): ?string
    {
        if ($this->isInWarehouse() || $this->isCurrentStatus(RefStatus::ID_DELIVERY_IN_PROGRESS)) {
            return null;
        }

        return DateHelper::getTime($this->getStatusByCode(RefStatus::CODE_APPROXIMATE_DELIVERY_TO_CITY, true)?->created_at);
    }

    public function getTimerMinutes(): ?int
    {
        return $this->sla_date
            ? Carbon::make(now())->diffInMinutes($this->sla_date, false)
            : null;
    }

    public function getTimerTime(): ?string
    {
        $minutes = $this->getTimerMinutes();

        try {
            return $minutes
                ? ($minutes < 0 ? '-' : '') . CarbonInterval::minutes($minutes)->cascade()->forHumans(null, true)
                : null;
        } catch (Exception $e) {
            return null;
        }
    }

    public function getStatusForWave(): ?string
    {
        $timer = (int)$this->getTimerMinutes();

        if ($timer < 0) {
            return self::STATUS_LATE_TITLE;
        }

        return $this->isDeliveringToCity()
            ? self::STATUS_DELIVERING_TITLE
            : self::STATUS_FACT_TITLE;
    }

    public function getStatusIdWave(): ?int
    {
        return $this->isDeliveringToCity()
            ? self::STATUS_DELIVERING
            : self::STATUS_FACT;
    }

    public function getStopsWithPreviousInvoiceCoordinate(?string $previousCoordinate): int|null
    {
        return $previousCoordinate !== $this->getReceiverCoordinate()
            ? 1
            : null;
    }

    public function additionalServiceValues(): MorphMany
    {
        return $this->morphMany(AdditionalServiceValue::class, 'client');
    }

    public function courierPayments(): MorphMany
    {
        return $this->morphMany(CourierPayment::class, 'client');
    }

    public function hasAdditionalServices(): bool
    {
        return $this->additionalServiceValues->isNotEmpty();
    }

    public function hasAdditionalServiceValueByTypeId(int $typeId): bool
    {
        return (bool)$this->additionalServiceValues?->where('type_id', $typeId)->isNotEmpty();
    }

    public function hasTransit(): bool
    {
        return $this->receiver?->dispatcher_sector_id !== $this->order?->sender?->dispatcher_sector_id;
    }

    public function setCargoType(): void
    {
        $cargo = $this->cargo;

        $this->cargo_type = CargoHelper::getType(
            $cargo->depth,
            $cargo->height,
            $cargo->width,
            $cargo->volume
        );
    }

    public function hasCashPaymentMethod(): bool
    {
        return $this->payment_method === PaymentMethodCodes::CASH;
    }

    public function hasKaspiPayPaymentMethod(): bool
    {
        return $this->payment_method == PaymentMethodCodes::KASPI_PAY;
    }

    public function hasSenderPaymentType(): bool
    {
        return $this->payment_type === PaymentTypeCodes::SENDER;
    }

    public function getPaymentTypeTitle(): string
    {
        return match ($this->payment_type) {
            PaymentTypeCodes::SENDER   => 'Отправителем',
            PaymentTypeCodes::RECEIVER => 'Получателем',
            default                    => 'Третье лицо',
        };
    }

    public function container(): HasOneThrough
    {
        return $this->hasOneThrough(Container::class, ContainerInvoice::class, 'invoice_id', 'id', 'id', 'container_id');
    }

    public function getReceiverCoordinate(): ?string
    {
        return $this->receiver->latitude . '-' . $this->receiver->longitude;
    }

    /**
     * @param Builder $query
     * @param $latitude
     * @param $longitude
     * @return Builder
     */
    public function scopeFilterByLocationAndDistance(Builder $query, $latitude, $longitude): Builder
    {
        $latitude  = (double) $latitude;
        $longitude = (double) $longitude;

        return $query
            ->select([
                'invoices.id',
                'invoices.invoice_number',
                'invoices.cargo_type'
            ])
            ->addSelect([
                app('db')->raw(
                    "(6371 * acos(cos(radians($latitude)) * cos(radians(receivers.latitude)) * cos(radians(receivers.longitude) -
                radians($longitude)) + sin(radians($latitude)) * sin(radians(receivers.latitude)))) AS distance"
                ),
                'receivers.latitude',
                'receivers.longitude',
            ])
            ->join('receivers', 'receivers.id', '=', 'invoices.receiver_id')
            ->orderBy('distance');
    }

    public function files(): MorphMany
    {
        return $this->morphMany(File::class, 'client');
    }

    public function file(): MorphOne
    {
        return $this->morphOne(File::class, 'client');
    }

    public function scan(): MorphOne
    {
        return $this->file()
            ->where('type', File::TYPE_INVOICE_SCAN)
            ->latestOfMany();
    }

    public function getPeriodTitle(): array
    {
        return [$this->take_date,
            $this->period?->title ?: $this->take_time];
    }

    public function shortcomingReportFiles(): ?Collection
    {
        return $this->files
            ->filter(function (File $file) {
                return $file->type === File::TYPE_COURIER_SHORTCOMING_REPORT;
            });
    }

    public function shortcomingProductFiles(): ?Collection
    {
        return $this->files
            ->filter(function (File $file) {
                return $file->type === File::TYPE_COURIER_SHORTCOMING_PRODUCT;
            });
    }

    public function getProblems(): SupportCollection
    {
        /** @var InvoiceService $service */
        $service = app(InvoiceService::class);
        return $service->getProblems($this);
    }

    public function hasProblems(): bool
    {
        return $this->getProblems()->isNotEmpty();
    }

    public function courierPaymentsForRoad(): ?Collection
    {
        return $this->files
            ->filter(function (File $file) {
                return $file->type === File::TYPE_COURIER_ROAD_CHECK;
            });
    }

    public function courierPaymentsForParking(): ?Collection
    {
        return $this->files
            ->filter(function (File $file) {
                return $file->type === File::TYPE_COURIER_PARKING_CHECK;
            });
    }

    public function shouldBeVerified(): ?int
    {
        if (Str::endsWith($this->invoice_number, self::RETURN_INVOICE_SUFFIXES)) {
            return 0;
        }

        return $this->verify;
    }

    public function hasReceiverPaymentType(): bool
    {
        return $this->payment_type === PaymentTypeCodes::RECEIVER;
    }

    public function canGenerateReceiverQr(): bool
    {
        if ($this->getStatusByCode(RefStatus::CODE_PAID)) {
            return false;
        }

        if ($this->cargo->cod_payment > 0) {
            return true;
        }

        return $this->hasReceiverPaymentType() &&
            ($this->hasCashPaymentMethod() ||
                $this->hasKaspiPayPaymentMethod());
    }

    public function getVerifyInvoiceNumber(): ?string
    {
        if ($this->shouldBeVerified() === VerificationTypeEnum::KASPI_VERIFICATION_TYPE_ID->value) {
            return $this->dop_invoice_number;
        }

        if ($this->order?->company_id === Company::COMPANY_JPOST_ID) {
            return $this->dop_invoice_number;
        }

        return $this->invoice_number;
    }

    public function isCanceled(): bool
    {
        if (is_null($this->status_id)) {
            return false;
        }

        return in_array($this->status_id, [RefStatus::ID_CANCELLED, RefStatus::ID_ORDER_CANCELED]);
    }

    public function getWaitListConfirmed()
    {
        return $this->waitListStatuses
            ->where('state_id', WaitListStatus::ID_CONFIRMED);
    }

    public function getWaitListNotConfirmed()
    {
        return $this->waitListStatuses
            ->where('state_id', WaitListStatus::ID_IN_WORK_AT_CC)
            ->filter(function (WaitListStatus $status) {
                return $status->child->isEmpty();
            });
    }

    public function isWaitListOnDelivery(): bool
    {
        return in_array($this->waitListStatus?->code, RefStatus::WAIT_LIST_STATUS_CODES_DELIVERY, true);
    }

    public function getDirection(): string
    {
        return $this->order?->sender?->city?->name . ' - ' . $this->receiver?->city?->name;
    }
}
