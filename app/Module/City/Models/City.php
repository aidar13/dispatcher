<?php

declare(strict_types=1);

namespace App\Module\City\Models;

use Database\Factories\CityFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property int $region_id
 * @property int $type_id
 * @property string|null $code
 * @property string|null $coordinates
 * @property float|string|null $latitude
 * @property float|string|null $longitude
 */
final class City extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected static function newFactory(): CityFactory
    {
        return CityFactory::new();
    }
}
