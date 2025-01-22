<?php

declare(strict_types=1);

namespace App\Module\Delivery\Models;

use App\Helpers\GeoCoordinateHelper;
use App\Module\City\Models\City;
use App\Module\Company\Models\Company;
use App\Module\Courier\Models\Courier;
use App\Module\CourierApp\Models\CourierState;
use App\Module\Order\Models\Invoice;
use App\Module\Planning\Models\Container;
use App\Module\Planning\Models\ContainerInvoice;
use App\Module\Routing\Models\RoutingItem;
use App\Module\Status\Models\OrderStatus;
use App\Module\Status\Models\RefStatus;
use App\Module\Status\Models\StatusType;
use App\Module\Take\Models\Customer;
use App\Module\Take\Models\OrderTake;
use Database\Factories\DeliveryFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Module\Delivery\Models\Delivery
 *
 * @property int $id
 * @property int $status_id
 * @property int $invoice_id
 * @property string|null $invoice_number
 * @property int $customer_id
 * @property int $courier_id
 * @property int $city_id
 * @property int $company_id
 * @property int|null $wait_list_status_id
 * @property int $places
 * @property float|null $weight
 * @property float|null $volume
 * @property float|null $volume_weight
 * @property string|null $delivery_receiver_name
 * @property string|null $courier_comment
 * @property string|null $delivered_at
 * @property int|null $internal_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Customer|null $customer
 * @property-read Courier|null $courier
 * @property-read Company|null $company
 * @property-read StatusType|null $status
 * @property-read Invoice|null $invoice
 * @property-read City|null $city
 * @property-read RouteSheetInvoice|null $routeSheetInvoice
 * @property-read Container|null $container
 * @property-read Collection|OrderStatus[] $statuses
 * @property-read RefStatus|null $refStatus
 * @property-read RoutingItem|null $routingItem
 * @property-read CourierState[]|Collection $courierStates
 * @property-read RefStatus|null $waitListStatus
 * @property-read OrderTake[]|Collection $courierTakes
 * @method static DeliveryFactory factory($count = null, $state = [])
 */
final class Delivery extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const STATUS_DELIVERED           = 0;
    public const STATUS_RECEIVER_CANCELED   = 1;
    public const STATUS_DATE_CHANGE         = 2;
    public const STATUS_PICKUP              = 3;
    public const STATUS_CARGO_RETURNED      = 4;
    public const STATUS_RECEIVER_MISSING    = 5;

    public const BUCKET_DELIVERY_PATH = 'delivery_attachments';

    protected static function newFactory(): DeliveryFactory
    {
        return DeliveryFactory::new();
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function waitListStatus(): BelongsTo
    {
        return $this->belongsTo(RefStatus::class, 'wait_list_status_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(StatusType::class, 'status_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function courier(): BelongsTo
    {
        return $this->belongsTo(Courier::class, 'courier_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function refStatus(): BelongsTo
    {
        return $this->belongsTo(RefStatus::class, 'wait_list_status_id');
    }

    public function courierStates(): MorphMany
    {
        return $this->morphMany(CourierState::class, 'client');
    }

    public function hasState(): bool
    {
        return $this->courierStates->isNotEmpty();
    }

    public function getStatusFromIntegration(?int $status): int
    {
        return match ($status) {
            0 => StatusType::ID_DELIVERED,
            1 => StatusType::ID_RECEIVER_CANCELED,
            2 => StatusType::ID_DATE_CHANGE,
            3 => StatusType::ID_PICKUP,
            4 => StatusType::ID_CARGO_RETURNED,
            5 => StatusType::ID_RECEIVER_MISSING,
            default => StatusType::ID_IN_DELIVERING,
        };
    }

    public function statuses(): HasMany
    {
        return $this->hasMany(OrderStatus::class, 'invoice_id', 'invoice_id');
    }

    public function container(): HasOneThrough
    {
        return $this->hasOneThrough(
            Container::class,
            ContainerInvoice::class,
            'invoice_id',
            'id',
            'invoice_id',
            'container_id'
        );
    }

    public function setWaitListStatus($statusId): void
    {
        $this->wait_list_status_id = $statusId;
    }

    public function setStatusId(?int $statusId): void
    {
        $this->status_id = $statusId;
    }

    public function routeSheetInvoice(): BelongsTo
    {
        return $this->belongsTo(RouteSheetInvoice::class, 'invoice_id', 'invoice_id');
    }

    public function courierTakes(): HasMany
    {
        return $this->hasMany(OrderTake::class, 'courier_id', 'courier_id');
    }

    public function hasCodeInStatuses(int $code): bool
    {
        return $this->statuses->contains('code', $code);
    }

    public function isRemained(): bool
    {
        return !in_array($this->status_id, [StatusType::ID_CARGO_RETURNED, StatusType::ID_DELIVERED, StatusType::ID_RECEIVER_CANCELED]);
    }

    public function isDelivered(): bool
    {
        return $this->status_id === StatusType::ID_DELIVERED;
    }

    public function isReturned(): bool
    {
        return in_array($this->status_id, [StatusType::ID_CARGO_RETURNED, StatusType::ID_RECEIVER_CANCELED]);
    }

    public function getStatusForOneC(): string|null
    {
        return match ($this->status_id) {
            StatusType::ID_DELIVERED         => (string)self::STATUS_DELIVERED,
            StatusType::ID_RECEIVER_CANCELED => (string)self::STATUS_RECEIVER_CANCELED,
            StatusType::ID_DATE_CHANGE       => (string)self::STATUS_DATE_CHANGE,
            StatusType::ID_PICKUP            => (string)self::STATUS_PICKUP,
            StatusType::ID_CARGO_RETURNED    => (string)self::STATUS_CARGO_RETURNED,
            StatusType::ID_RECEIVER_MISSING  => (string)self::STATUS_RECEIVER_MISSING,
            default => null,
        };
    }

    public function getWaitListStatusCode(): ?int
    {
        if (in_array($this->status_id, [StatusType::ID_DELIVERED, StatusType::ID_RECEIVER_CANCELED])) {
            return null;
        }

        return $this->refStatus?->code;
    }

    /**
     * @psalm-suppress InvalidScalarArgument
     */
    public function getNearTakeInfoIds(): \Illuminate\Support\Collection
    {
        $nearOrderTakeIds = collect();

        $this->courierTakes->each(function (OrderTake $orderTake) use ($nearOrderTakeIds) {
            if (
                $this->isNearTakeInfo(
                    (float)$orderTake->order?->sender?->latitude,
                    (float)$orderTake->order?->sender?->longitude,
                )
            ) {
                $nearOrderTakeIds->push($orderTake->order_id);
            }
        });

        return $nearOrderTakeIds;
    }

    public function isNearTakeInfo(float|null $takeLatitude, float|null $takeLongitude): bool
    {
        if (!$takeLatitude || !$takeLongitude || !$this->customer?->latitude || !$this->customer?->longitude) {
            return false;
        }
        return GeoCoordinateHelper::isNearDistance(
            (float)$this->customer?->latitude,
            (float)$this->customer?->longitude,
            $takeLatitude,
            $takeLongitude,
        );
    }

    public function isCanceled(): bool
    {
        return in_array($this->status_id, [StatusType::ID_TAKE_CANCELED, StatusType::ID_RECEIVER_CANCELED]);
    }

    public function routingItem(): HasOne
    {
        return $this->hasOne(RoutingItem::class, 'client_id', 'invoice_id')
            ->where('client_type', Invoice::class)
            ->whereDate('created_at', now()->format('Y-m-d'))
            ->orderByDesc('id');
    }
}
