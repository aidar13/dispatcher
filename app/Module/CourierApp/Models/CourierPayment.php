<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Models;

use App\Models\User;
use App\Module\Courier\Models\Courier;
use App\Module\File\Models\File;
use App\Module\Order\Models\Invoice;
use App\Module\Order\Models\Order;
use Carbon\Carbon;
use Database\Factories\CourierPaymentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property int|null $courier_id
 * @property int $user_id
 * @property int $client_id
 * @property string $client_type
 * @property int $type
 * @property int $cost
 * @property-read Courier $courier
 * @property-read Invoice|Order $client
 * @property-read User $user
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 */
final class CourierPayment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'courier_payments';

    protected $fillable = [
        'id',
        'courier_id',
        'client_id',
        'client_type',
        'type',
        'cost',
        'deleted_at',
    ];

    public const TYPE_COST_FOR_ROAD    = 1;
    public const TYPE_COST_FOR_PARKING = 2;

    public const COST_FOR_ROAD_NAME    = 'Проезд';
    public const COST_FOR_PARKING_NAME = 'Парковка';

    protected static function newFactory(): CourierPaymentFactory
    {
        return CourierPaymentFactory::new();
    }

    public function courier(): BelongsTo
    {
        return $this->belongsTo(Courier::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function client(): MorphTo
    {
        return $this->morphTo();
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setCourierId(?int $courierId): void
    {
        $this->courier_id = $courierId;
    }

    public function setUserId(int $userId): void
    {
        $this->user_id = $userId;
    }

    public function setClientId(int $clientId): void
    {
        $this->client_id = $clientId;
    }

    public function setClientType(string $clientType): void
    {
        $this->client_type = $clientType;
    }

    public static function getClientTypeByClientType(string $clientType): string
    {
        return match ($clientType) {
            'App\Module\Order\Models\Order'              => Order::class,
            'App\Module\Order\Models\OrderLogisticsInfo' => Invoice::class,
        };
    }

    public function setType(int $type): void
    {
        $this->type = $type;
    }

    public function setCost(int|float $cost): void
    {
        $this->cost = $cost * 100;
    }

    public function getCost(): float|int|null
    {
        return $this->cost ? $this->cost / 100 : null;
    }

    public static function getSumCostForRoad(Collection $courierPayments): float|int|null
    {
        return $courierPayments->where('type', self::TYPE_COST_FOR_ROAD)->sum('cost') / 100;
    }

    public static function getSumCostForParking(Collection $courierPayments): float|int|null
    {
        return $courierPayments->where('type', self::TYPE_COST_FOR_PARKING)->sum('cost') / 100;
    }

    public function getTypeName(): string
    {
        return $this->type === self::TYPE_COST_FOR_ROAD
            ? self::COST_FOR_ROAD_NAME
            : self::COST_FOR_PARKING_NAME;
    }

    public function getFileType(): int
    {
        return $this->type === self::TYPE_COST_FOR_ROAD
            ? File::TYPE_COURIER_ROAD_CHECK
            : File::TYPE_COURIER_PARKING_CHECK;
    }

    public function getFiles(): ?Collection
    {
        return $this->type === self::TYPE_COST_FOR_ROAD
            ? $this->client->courierPaymentsForRoad()
            : $this->client->courierPaymentsForParking();
    }
}
