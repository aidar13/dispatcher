<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Models;

use App\Module\Courier\Models\Courier;
use Database\Factories\CourierLoactionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $courier_id
 * @property string|null $latitude
 * @property string|null $longitude
 * @property int|null $downtime
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Courier $courier
 */
final class CourierLoaction extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'courier_locations';

    public const DEFAULT_DOWNTIME_MINUTES = 15;
    public const DEFAULT_DOWNTIME_RADIUS  = 100;

    protected static function newFactory(): CourierLoactionFactory
    {
        return CourierLoactionFactory::new();
    }

    public function courier(): BelongsTo
    {
        return $this->belongsTo(Courier::class);
    }
}
