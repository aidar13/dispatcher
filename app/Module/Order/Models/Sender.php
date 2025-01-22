<?php

namespace App\Module\Order\Models;

use App\Module\City\Models\City;
use App\Module\DispatcherSector\Models\Sector;
use Database\Factories\SenderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int|null $city_id
 * @property string|null $full_address
 * @property string|null $title
 * @property string|null $full_name
 * @property string|null $phone
 * @property string|null $additional_phone
 * @property string|null $latitude
 * @property string|null $longitude
 * @property string|null $street
 * @property string|null $house
 * @property string|null $office
 * @property string|null $index
 * @property string|null $comment
 * @property int|null $warehouse_id
 * @property int|null $dispatcher_sector_id
 * @property int|null $sector_id
 * @property Carbon|mixed|null $created_at
 * @property-read City|null $city
 * @property-read Sector|null $sector
 * @property-read Order|null $order
 */
final class Sender extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'senders';

    protected static function newFactory(): SenderFactory
    {
        return SenderFactory::new();
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function order(): HasOne
    {
        return $this->hasOne(Order::class);
    }

    public function sector(): BelongsTo
    {
        return $this->belongsTo(Sector::class, 'sector_id');
    }

    public function isSelfDelivery(): bool
    {
        return $this->warehouse_id !== null;
    }
}
