<?php

namespace App\Module\Order\Models;

use App\Module\City\Models\City;
use App\Module\DispatcherSector\Models\DispatcherSector;
use App\Module\DispatcherSector\Models\Sector;
use Database\Factories\ReceiverFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $city_id
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
 * @property-read DispatcherSector|null $dispatcherSector
 * @property-read Sector|null $sector
 * @property-read City|null $city
 */
final class Receiver extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'receivers';

    protected static function newFactory(): ReceiverFactory
    {
        return ReceiverFactory::new();
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class, 'receiver_id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function sector(): BelongsTo
    {
        return $this->belongsTo(Sector::class, 'sector_id');
    }

    public function dispatcherSector(): BelongsTo
    {
        return $this->belongsTo(DispatcherSector::class, 'dispatcher_sector_id');
    }

    public function isPickup(): bool
    {
        return (bool)$this->warehouse_id;
    }

    public function getShortAddress(): string
    {
        return $this->street . ', ' . $this->house;
    }
}
