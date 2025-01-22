<?php

namespace App\Module\DispatcherSector\Models;

use App\Module\Order\Models\Invoice;
use App\Module\Order\Models\Receiver;
use App\Module\Planning\Models\Container;
use Database\Factories\SectorFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;

/**
 * @property int $id
 * @property string $name
 * @property int $dispatcher_sector_id
 * @property string|null $coordinates
 * @property string|null $polygon
 * @property string $color
 * @property string $latitude
 * @property string $longitude
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 * @property-read DispatcherSector|null $dispatcherSector
 * @property-read Collection|Invoice[] $invoices
 * @property-read Collection|Container[] $containers
 */
final class Sector extends Model
{
    use SoftDeletes;
    use HasFactory;
    use HasSpatial;

    protected $table = 'sectors';

    public const ONE_C_NANE = 'Сектор';

    protected static function newFactory(): SectorFactory
    {
        return SectorFactory::new();
    }

    public function dispatcherSector(): BelongsTo
    {
        return $this->belongsTo(DispatcherSector::class, 'dispatcher_sector_id');
    }

    public function invoices(): HasManyThrough
    {
        return $this->hasManyThrough(Invoice::class, Receiver::class, 'sector_id', 'receiver_id')
            ->with(['cargo', 'receiver']);
    }

    public function containers(): HasMany
    {
        return $this->hasMany(Container::class)
            ->with(['invoices.waitListStatus:id,name', 'status']);
    }

    public function getNameToYandex(): string
    {
        return $this->dispatcherSector->name . '-' . $this->name;
    }
}
