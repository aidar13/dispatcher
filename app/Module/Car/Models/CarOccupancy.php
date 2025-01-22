<?php

declare(strict_types=1);

namespace App\Module\Car\Models;

use App\Models\User;
use App\Module\Order\Models\Invoice;
use App\Module\Order\Models\Order;
use Carbon\Carbon;
use Database\Factories\CarOccupancyFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int|null $car_occupancy_type_id
 * @property int|null $car_id
 * @property int|null $user_id
 * @property int $type_id
 * @property Carbon|string|null $created_at
 * @property Carbon|string|null $updated_at
 * @property Carbon|string|null $deleted_at
 * @property int|null $client_id
 * @property string|null $client_type
 * @property-read Car|null $car
 * @property-read User|null $user
 * @property-read Order|Invoice|null $occupancy
 * @property-read CarOccupancyType $carOccupancyType
 */
final class CarOccupancy extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'car_occupancies';

    public const COURIER_WORK_TYPE_ID_TAKE     = 1;
    public const COURIER_WORK_TYPE_ID_DELIVERY = 2;

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    protected static function newFactory(): CarOccupancyFactory
    {
        return CarOccupancyFactory::new();
    }

    public function occupancy(): MorphTo
    {
        return $this->morphTo();
    }

    public function carOccupancyType(): BelongsTo
    {
        return $this->belongsTo(CarOccupancyType::class, 'car_occupancy_type_id', 'id');
    }
}
