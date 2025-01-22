<?php

declare(strict_types=1);

namespace App\Module\Order\Models;

use App\Module\Planning\Models\Container;
use Database\Factories\FastDeliveryOrderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $container_id
 * @property string|null $courier_name
 * @property string|null $courier_phone
 * @property int|null $type
 * @property int|null $internal_id # Внутренний идентификатор заказа быстрой доставки
 * @property string|null $price # Цена за доставку у провайдера быстрой доставки
 * @property string|null $internal_status # Статус быстрой доставки у провайдера
 * @property string|null $tracking_url
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Container $container
 */
final class FastDeliveryOrder extends Model
{
    use HasFactory;

    public const RAKETA_DEFAULT_COURIER      = 'RAKETA';
    public const YANDEX_DEFAULT_COURIER      = 'YANDEX';
    public const MIG_EXPRESS_DEFAULT_COURIER = 'MIG-EXPRESS';

    public const TYPE_RAKETA          = 1;
    public const TYPE_MIG_EXPRESS     = 2;
    public const TYPE_YANDEX_DELIVERY = 3;

    public const YANDEX_MAX_WEIGHT = 300;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    protected static function newFactory(): FastDeliveryOrderFactory
    {
        return FastDeliveryOrderFactory::new();
    }

    public function container(): BelongsTo
    {
        return $this->belongsTo(Container::class);
    }

    public function getCourier(): ?string
    {
        return $this->courier_name;
    }

    public function getProviderName(): ?string
    {
        return match ($this->type) {
            self::TYPE_YANDEX_DELIVERY => self::YANDEX_DEFAULT_COURIER,
            self::TYPE_MIG_EXPRESS     => self::MIG_EXPRESS_DEFAULT_COURIER,
            self::TYPE_RAKETA          => self::RAKETA_DEFAULT_COURIER,
            default                    => null,
        };
    }
}
