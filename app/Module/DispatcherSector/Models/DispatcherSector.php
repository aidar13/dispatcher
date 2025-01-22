<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Models;

use App\Models\User;
use App\Module\City\Models\City;
use App\Module\Courier\Models\Courier;
use App\Module\Order\Models\Invoice;
use App\Module\Order\Models\Receiver;
use Carbon\Carbon;
use Database\Factories\DispatcherSectorFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;

/**
 * App\Module\DispatcherSector\Models\DispatcherSector
 *
 * @property int $id
 * @property int $city_id
 * @property int|null $delivery_manager_id
 * @property int|null $default_sector_id
 * @property string $name
 * @property string $description
 * @property string|null $polygon
 * @property false|mixed|string|null $coordinates
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property int|null $courier_id
 * @property-read Collection|Sector[] $sectors
 * @property-read Sector|null $defaultSector
 * @property-read City $city
 * @property-read Collection|Invoice[] $invoices
 * @property-read Collection|User[] $dispatchers
 * @property-read Collection|Courier[] $couriers
 * @property-read Collection|Wave[] $waves
 * @property-read Collection|DispatchersSectorUser[] $dispatcherSectorUsers
 */
final class DispatcherSector extends Model
{
    use SoftDeletes;
    use HasFactory;
    use HasSpatial;

    protected $fillable = [
        'id',
        'name',
        'city_id',
        'polygon',
        'coordinates',
        'description',
        'created_at',
    ];

    protected static function newFactory(): DispatcherSectorFactory
    {
        return DispatcherSectorFactory::new();
    }

    public function sectors(): HasMany
    {
        return $this->hasMany(Sector::class, 'dispatcher_sector_id');
    }

    public function waves(): HasMany
    {
        return $this->hasMany(Wave::class, 'dispatcher_sector_id');
    }

    public function couriers(): HasMany
    {
        return $this->hasMany(Courier::class, 'dispatcher_sector_id');
    }

    public function defaultSector(): BelongsTo
    {
        return $this->belongsTo(Sector::class, 'default_sector_id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function invoices(): HasManyThrough
    {
        return $this->hasManyThrough(Invoice::class, Receiver::class, 'dispatcher_sector_id', 'receiver_id')
            ->with('cargo');
    }

    public function dispatchers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, DispatchersSectorUser::class, 'dispatcher_sector_id', 'user_id');
    }

    public function dispatcherSectorUsers(): HasMany
    {
        return $this->hasMany(DispatchersSectorUser::class, 'dispatcher_sector_id');
    }

    public function getRoutingEnabledCouriers(): Collection
    {
        return $this->couriers->where('routing_enabled', '=', 1);
    }
}
