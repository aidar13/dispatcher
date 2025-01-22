<?php

declare(strict_types=1);

namespace App\Module\Take\Models;

use App\Helpers\DateHelper;
use App\Module\City\Models\City;
use App\Module\Company\Models\Company;
use App\Module\Courier\Models\Courier;
use App\Module\CourierApp\Models\CourierState;
use App\Module\Order\Models\Invoice;
use App\Module\Order\Models\InvoiceCargo;
use App\Module\Order\Models\Order;
use App\Module\Order\Models\ShipmentType;
use App\Module\Routing\Models\RoutingItem;
use App\Module\Status\Models\OrderStatus;
use App\Module\Status\Models\RefStatus;
use App\Module\Status\Models\StatusType;
use Database\Factories\OrderTakeFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection as SupportCollection;

/**
 * App\Module\Take\Models\OrderTake
 *
 * @property int $id
 * @property int $status_id
 * @property int $invoice_id
 * @property int $customer_id
 * @property int $courier_id
 * @property int $city_id
 * @property int $company_id
 * @property int|null $wait_list_status_id
 * @property string $take_date
 * @property int $shipment_type
 * @property int $places
 * @property float $weight
 * @property float $volume
 * @property int|null $internal_id
 * @property int|null $order_id
 * @property string|null $order_number
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null|string $deleted_at
 * @property-read Customer|null $customer
 * @property-read StatusType|null $status
 * @property-read ShipmentType|null $shipmentType
 * @property-read City|null $city
 * @property-read Courier|null $courier
 * @property-read Invoice|null $invoice
 * @property-read Invoice|null $dopInvoice
 * @property-read RefStatus|null $waitListStatus
 * @property-read Company|null $company
 * @property-read Order $order
 * @property-read Collection|OrderStatus[] $statuses
 * @property-read OrderStatus|null $takenStatus
 * @property-read RoutingItem|null $routingItem
 * @property-read Collection|CourierState[] $courierStates
 * @property-read InvoiceCargo|null $cargo
 * @method static OrderTakeFactory factory($count = null, $state = [])
 */
final class OrderTake extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const STATUS_TAKEN = 0;

    protected static function newFactory(): OrderTakeFactory
    {
        return OrderTakeFactory::new();
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(StatusType::class, 'status_id');
    }

    public function waitListStatus(): BelongsTo
    {
        return $this->belongsTo(RefStatus::class, 'wait_list_status_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function shipmentType(): BelongsTo
    {
        return $this->belongsTo(ShipmentType::class, 'shipment_type');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function courier(): BelongsTo
    {
        return $this->belongsTo(Courier::class, 'courier_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function cargo(): HasOne
    {
        return $this->hasOne(InvoiceCargo::class, 'invoice_id', 'invoice_id');
    }

    public function courierStates(): MorphMany
    {
        return $this->morphMany(CourierState::class, 'client');
    }

    public function statuses(): HasMany
    {
        return $this->hasMany(OrderStatus::class, 'invoice_id', 'invoice_id');
    }

    public function getStatusByCode(int $code, bool $sortByDesc = false): ?OrderStatus
    {
        return $this->statuses
            ->where('code', $code)
            ->sortBy(fn($item) => $item->id, 0, $sortByDesc)
            ->first();
    }

    public function takenStatus(): HasOne
    {
        return $this->hasOne(OrderStatus::class, 'order_id', 'order_id')
            ->where('code', RefStatus::CODE_CARGO_PICKED_UP);
    }

    public function routingItem(): HasOne
    {
        return $this->hasOne(RoutingItem::class, 'client_id', 'order_id')
            ->where('client_type', Order::class)
            ->whereDate('created_at', now()->format('Y-m-d'))
            ->orderByDesc('id');
    }

    public function getTakenAtByStatuses(): ?OrderStatus
    {
        return $this->statuses->where('code', RefStatus::CODE_CARGO_PICKED_UP)->first();
    }

    public function setWaitListStatus($statusId): void
    {
        $this->wait_list_status_id = $statusId;
    }

    public function isStatusTaken(): bool
    {
        return $this->status_id === StatusType::ID_TAKEN;
    }

    public function isStatusNotAssigned(): bool
    {
        return $this->status_id === StatusType::ID_NOT_ASSIGNED;
    }

    public function isStatusAssigned(): bool
    {
        return $this->status_id === StatusType::ID_ASSIGNED;
    }

    public function setTakeStatus(?int $status): void
    {
        $this->status_id = $status;
    }

    public function isStatusCancelled(): bool
    {
        return $this->status_id === StatusType::ID_TAKE_CANCELED;
    }

    public function isCompleted(): bool
    {
        return $this->status_id === StatusType::ID_CARGO_HANDLING;
    }

    public function isRemained(): bool
    {
        return !in_array($this->status_id, [StatusType::ID_CARGO_HANDLING, StatusType::ID_TAKE_CANCELED]);
    }

    public function getWaitListStatusCodeForOneC(): ?int
    {
        /** @var OrderStatus $orderStatus */
        $orderStatus = $this->invoice->statuses()
            ->where('code', $this->waitListStatus?->code)
            ->latest()
            ->first();

        return $orderStatus?->code;
    }

    public function getStatusForOneC(): string|null
    {
        return $this->status_id === StatusType::ID_TAKEN
            ? (string)self::STATUS_TAKEN
            : null;
    }

    public function getCourierStates(): SupportCollection
    {
        return $this->courierStates->map(fn(CourierState $courierState) => [
            'title' => CourierState::HERE,
            'date'  => DateHelper::getDateWithTime($courierState->created_at)
        ]);
    }

    public function getDirection(): string
    {
        return $this->order?->sender?->city?->name . ' - ' . $this->invoice?->receiver?->city?->name;
    }

    public function hasState(): bool
    {
        return $this->courierStates()->exists();
    }

    public function getOrderNumber(): ?string
    {
        return $this->order->number ?? $this->order_number;
    }

    public function dopInvoice(): HasOne
    {
        return $this->hasOne(Invoice::class, 'order_id', 'order_id')
            ->whereNotNull('type');
    }

    public function isDelaying(): bool
    {
        return (new Carbon($this->take_date))->lt(Carbon::now()->toDateString());
    }
}
