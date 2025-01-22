<?php

declare(strict_types=1);

namespace App\Module\Courier\Models;

use App\Module\DispatcherSector\Models\Sector;
use Database\Factories\CourierSectorFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $courier_id
 * @property int $sector_id
 * @property int $type
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property-read Sector $sector
 */
final class CourierSector extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'courier_sectors';

    public const TYPE_ALLOWED   = 1;
    public const TYPE_FORBIDDEN = 2;
    public const TYPE_OPTIONAL  = 3;

    protected static function newFactory(): CourierSectorFactory
    {
        return CourierSectorFactory::new();
    }

    public function sector(): BelongsTo
    {
        return $this->belongsTo(Sector::class);
    }
}
