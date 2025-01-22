<?php

declare(strict_types=1);

namespace App\Module\Car\Models;

use Database\Factories\CarTypeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $title
 * @property int $capacity
 * @property int|float $volume
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 */
final class CarType extends Model
{
    use HasFactory;
    use SoftDeletes;

    public static function newFactory(): CarTypeFactory
    {
        return CarTypeFactory::new();
    }
}
