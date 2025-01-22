<?php

declare(strict_types=1);

namespace App\Module\Order\Models;

use App\Module\Company\Models\Company;
use App\Module\CourierApp\Models\CourierPayment;
use App\Module\File\Models\File;
use App\Module\Notification\Models\WaitListMessage;
use App\Module\Order\Contracts\Services\OrderService;
use App\Module\Status\Models\WaitListStatus;
use App\Module\Take\Models\OrderTake;
use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property int|null $company_id
 * @property string|null $number
 * @property int|null $sender_id
 * @property int $user_id
 * @property string $source
 * @property int|null $parent_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Invoice[] $invoices
 * @property-read Invoice|null $dopInvoice
 * @property-read int $invoices_count
 * @property-read Collection|OrderTake[] $orderTakes
 * @property-read OrderTake $take
 * @property-read Company $company
 * @property-read Sender $sender
 * @property-read WaitListMessage $lastWaitListMessage
 * @property-read CourierPayment[]|Collection $courierPayments
 * @property-read Collection|File[] $files
 * @property-read Order $cancelledOrder
 * @property-read WaitListStatus[]|Collection $waitListStatuses
 * @property-read WaitListStatus|null $waitListStatus
 */
final class Order extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'orders';

    protected $casts = [
        'company_id' => 'integer',
    ];

    protected static function newFactory(): OrderFactory
    {
        return OrderFactory::new();
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function dopInvoice(): HasOne
    {
        return $this->hasOne(Invoice::class)
            ->whereNotNull('type');
    }

    public function orderTakes(): HasMany
    {
        return $this->hasMany(OrderTake::class);
    }

    public function take(): HasOne
    {
        return $this->hasOne(OrderTake::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(Sender::class, 'sender_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function waitListStatuses(): MorphMany
    {
        return $this->morphMany(WaitListStatus::class, 'client');
    }

    public function waitListStatus(): MorphOne
    {
        return $this->morphOne(WaitListStatus::class, 'client');
    }

    public function courierPayments(): MorphMany
    {
        return $this->morphMany(CourierPayment::class, 'client');
    }

    public function cancelledOrder(): HasOne
    {
        return $this->hasOne(Order::class, 'parent_id');
    }

    public function getNumber(?string $orderNumber = null): ?string
    {
        $number = $this->number ?? $orderNumber;

        return Str::contains($number, Invoice::RETURN_INVOICE_SUFFIXES[0])
            ? Str::replaceLast(Invoice::RETURN_INVOICE_SUFFIXES[0], "", $number)
            : $number;
    }

    public function lastWaitListMessage(): HasOne
    {
        return $this->hasOne(WaitListMessage::class, 'number', 'number')
            ->where('type', WaitListMessage::TAKE_CARGO_TYPE)
            ->latest();
    }

    /**
     * Номера заказов в 1С обнуляются каждый код. Поэтому при отправке данных
     * по заказу в 1С, мы должны отправлять год создания заказа
     */
    public function getYear(): ?int
    {
        return $this->created_at->year;
    }

    public function files(): MorphMany
    {
        return $this->morphMany(File::class, 'client');
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

    public function hasPackType(): bool
    {
        foreach ($this->orderTakes as $orderTake) {
            if ($orderTake->cargo?->size_type) {
                return true;
            }
        }

        return false;
    }

    public function getProblems(): SupportCollection
    {
        /** @var OrderService $service */
        $service = app(OrderService::class);
        return $service->getProblems($this);
    }
}
