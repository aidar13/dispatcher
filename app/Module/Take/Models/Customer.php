<?php

declare(strict_types=1);

namespace App\Module\Take\Models;

use App\Module\Delivery\Models\Delivery;
use App\Module\DispatcherSector\Models\Sector;
use Database\Factories\CustomerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Module\Take\Models\Customer
 *
 * @property int $id
 * @property int|null $dispatcher_sector_id
 * @property int|null $sector_id
 * @property string $full_name
 * @property string $phone
 * @property string|null $additional_phone
 * @property string $address
 * @property string|null $latitude
 * @property string|null $longitude
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Sector|null $sector
 * @property-read OrderTake|null $take
 * @method static CustomerFactory factory($count = null, $state = [])
 */
final class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected static function newFactory(): CustomerFactory
    {
        return CustomerFactory::new();
    }

    public function sector(): BelongsTo
    {
        return $this->belongsTo(Sector::class, 'sector_id');
    }

    public function take(): HasOne
    {
        return $this->hasOne(OrderTake::class, 'customer_id');
    }

    public function delivery(): HasOne
    {
        return $this->hasOne(Delivery::class, 'customer_id');
    }
}
