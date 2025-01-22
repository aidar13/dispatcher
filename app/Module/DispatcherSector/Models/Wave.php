<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Models;

use App\Helpers\DateHelper;
use App\Module\Order\Models\Invoice;
use Database\Factories\WaveFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $title
 * @property int $dispatcher_sector_id
 * @property string $from_time
 * @property string $to_time
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property-read DispatcherSector|null $dispatchersSector
 * @property-read Collection|Invoice[] $invoices
 */
final class Wave extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const NEXT_DAY_PLANNING_TIME = 18;

    protected static function newFactory(): WaveFactory
    {
        return WaveFactory::new();
    }

    public function dispatcherSector(): BelongsTo
    {
        return $this->belongsTo(DispatcherSector::class, 'dispatcher_sector_id');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'wave_id');
    }

    public function getPlanningDate(): ?string
    {
        $date = now()->hour >= self::NEXT_DAY_PLANNING_TIME
            ? now()->addDay()
            : now();

        return DateHelper::getDate($date);
    }
}
