<?php

declare(strict_types=1);

namespace App\Module\Delivery\Models;

use App\Module\City\Models\City;
use App\Module\Courier\Models\Courier;
use Database\Factories\RouteSheetFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Module\Delivery\Models\RouteSheet
 *
 * @property int $id
 * @property string $number
 * @property int $status_id
 * @property int|null $dispatcher_sector_id
 * @property string $date
 * @property int $courier_id
 * @property int $city_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Courier|null $courier
 * @property-read Collection|RouteSheetInvoice[] $routeSheetInvoices
 * @property-read Collection|Delivery[] $deliveries
 * @property-read City|null $city
 * @method static RouteSheetFactory factory($count = null, $state = [])
 */
final class RouteSheet extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected static function newFactory(): RouteSheetFactory
    {
        return RouteSheetFactory::new();
    }

    public const ID_IN_PROGRESS = 1;
    public const ID_COMPLETED   = 2;

    public const IN_PROGRESS = "В работе";
    public const COMPLETED   = "Завершен";

    public function deliveries(): HasManyThrough
    {
        return $this->hasManyThrough(
            Delivery::class,
            RouteSheetInvoice::class,
            'route_sheet_id',
            'invoice_id',
            'id',
            'invoice_id'
        );
    }

    public function courier(): BelongsTo
    {
        return $this->belongsTo(Courier::class, 'courier_id');
    }

    public function routeSheetInvoices(): HasMany
    {
        return $this->hasMany(RouteSheetInvoice::class, 'route_sheet_id', 'id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function getStatusName(): array
    {
        $name = match ($this->status_id) {
            self::ID_COMPLETED   => self::COMPLETED,
            default => self::IN_PROGRESS,
        };

        return [
            'id'   => $this->status_id,
            'name' => $name
        ];
    }

    public function setDispatcherSectorId(?int $dispatcher_sector_id = null): void
    {
        $this->dispatcher_sector_id = $dispatcher_sector_id;
    }
}
