<?php

declare(strict_types=1);

namespace App\Module\Car\Models;

use Carbon\Carbon;
use Database\Factories\CarOccupancyTypeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $title
 * @property int $percent
 * @property Carbon|null $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class CarOccupancyType extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const ID_EMPTY       = 1;
    public const ID_25_PERCENT  = 2;
    public const ID_50_PERCENT  = 3;
    public const ID_75_PERCENT  = 4;
    public const ID_100_PERCENT = 5;

    protected $table = 'car_occupancy_types';

    protected static function newFactory(): CarOccupancyTypeFactory
    {
        return CarOccupancyTypeFactory::new();
    }
}
