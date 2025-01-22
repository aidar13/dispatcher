<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Models;

use Database\Factories\CourierStateFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $courier_id
 * @property int $client_id
 * @property string $client_type
 * @property string|null $latitude
 * @property string|null $longitude
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class CourierState extends Model
{
    use HasFactory;

    protected $table = 'courier_states';

    public const HERE = 'Я приехал';

    protected static function newFactory(): CourierStateFactory
    {
        return CourierStateFactory::new();
    }

    public function client(): MorphTo
    {
        return $this->morphTo();
    }
}
