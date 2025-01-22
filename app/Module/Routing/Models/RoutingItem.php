<?php

declare(strict_types=1);

namespace App\Module\Routing\Models;

use App\Module\Order\Models\Invoice;
use App\Module\Order\Models\Order;
use Carbon\Carbon;
use Database\Factories\RoutingItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $routing_id
 * @property int $type
 * @property int $client_id
 * @property string $client_type
 * @property int|null $position
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Order|Invoice $client
 */
final class RoutingItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public const TYPE_TAKE     = 1;
    public const TYPE_DELIVERY = 2;

    protected static function newFactory(): RoutingItemFactory
    {
        return RoutingItemFactory::new();
    }

    public function client(): MorphTo
    {
        return $this->morphTo();
    }

    public function isTypeTake(): bool
    {
        return $this->type === self::TYPE_TAKE;
    }

    public function isTypeDelivery(): bool
    {
        return $this->type === self::TYPE_DELIVERY;
    }
}
