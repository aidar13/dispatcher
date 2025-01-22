<?php

declare(strict_types=1);

namespace App\Module\Routing\Models;

use App\Models\User;
use App\Module\Courier\Models\Courier;
use App\Module\DispatcherSector\Models\DispatcherSector;
use App\Module\Planning\Models\Container;
use Carbon\Carbon;
use Database\Factories\RoutingFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int|null $courier_id
 * @property int|null $type
 * @property int|null $dispatcher_sector_id
 * @property string|null $task_id
 * @property int $user_id
 * @property string|null $response
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Courier|null $courier
 * @property-read DispatcherSector $dispatcherSector
 * @property-read Collection|RoutingItem[] $items
 * @property-read Collection|Container[] $containers
 */
final class Routing extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const TYPE_MULTIPLE_CAR = 1;
    public const TYPE_SINGLE_CAR   = 2;
    public const TYPE_DELIVERY     = 'delivery';
    public const TYPE_TAKE         = 'pickup';

    public const YANDEX_COMPANY_ID = 57783;

    protected static function newFactory(): RoutingFactory
    {
        return RoutingFactory::new();
    }

    public function courier(): BelongsTo
    {
        return $this->belongsTo(Courier::class, 'courier_id');
    }


    public function dispatcherSector(): BelongsTo
    {
        return $this->belongsTo(DispatcherSector::class, 'dispatcher_sector_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(RoutingItem::class, 'routing_id');
    }

    public function containers(): HasMany
    {
        return $this->hasMany(Container::class, 'routing_id');
    }

    public function isTypeSingleCar(): bool
    {
        return $this->type === self::TYPE_SINGLE_CAR;
    }
}
