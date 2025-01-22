<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Models;

use App\Module\Delivery\Models\Delivery;
use App\Module\Take\Models\OrderTake;
use Carbon\Carbon;
use Database\Factories\CourierCallFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property int $courier_id
 * @property int $client_id
 * @property string $client_type
 * @property string $phone
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read OrderTake|Delivery $client
 */
final class CourierCall extends Model
{
    use HasFactory;

    protected $table = 'courier_calls';

    public function client(): MorphTo
    {
        return $this->morphTo();
    }

    protected static function newFactory(): CourierCallFactory
    {
        return CourierCallFactory::new();
    }

    public function getClientTypeName(): string
    {
        return match ($this->client_type) {
            OrderTake::class => 'Забор',
            Delivery::class  => 'Доставка',
        };
    }
}
