<?php

declare(strict_types=1);

namespace App\Module\Order\Models;

use App\Traits\HasCrossDatabaseConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $type_id
 * @property int|null $status_id
 * @property string $client_type
 * @property int $client_id
 * @property float|null $value
 * @property float|null $cost_total
 * @property float|null $cost_per_hour
 * @property float|null $paid_price_per_hour
 * @property float|null $paid_price_total
 * @property int|null $duration
 * @property int|null $carrier_id
 * @property-read AdditionalServiceType|null $type
 */
final class AdditionalServiceValue extends Model
{
    use SoftDeletes;
    use HasCrossDatabaseConnection;

    protected $table = 'additional_service_values';

    public const TYPE_DELIVERY          = 1;
    public const TYPE_LOADER            = 2;
    public const TYPE_WOOD_BOX          = 3;
    public const TYPE_SOFT_PACKAGE      = 4;
    public const TYPE_PALLET            = 5;
    public const TYPE_GRID              = 6;
    public const TYPE_MANIPULATOR       = 7;
    public const TYPE_CRANE             = 8;
    public const TYPE_CAR               = 9;
    public const TYPE_HYDRAULIC_TROLLEY = 10;
    public const TYPE_RISE_TO_THE_FLOOR = 11;

    public const STATUS_CREATED   = 17;
    public const STATUS_COMPLETED = 18;
    public const STATUS_APPROVED  = 19;

    protected $fillable = [];

    public function client(): MorphTo
    {
        return $this->morphTo();
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(AdditionalServiceType::class);
    }
}
