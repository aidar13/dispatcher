<?php

declare(strict_types=1);

namespace App\Module\City\Models;

use Database\Factories\RegionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property int $country_id
 */
final class Region extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected static function newFactory(): RegionFactory
    {
        return RegionFactory::new();
    }
}
