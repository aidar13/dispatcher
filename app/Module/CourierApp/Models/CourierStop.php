<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Models;

use Database\Factories\CourierStopFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $courier_id
 * @property int $client_id
 * @property string $client_type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
final class CourierStop extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected static function newFactory(): CourierStopFactory
    {
        return CourierStopFactory::new();
    }

    public function client(): MorphTo
    {
        return $this->morphTo();
    }
}
