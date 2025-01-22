<?php

declare(strict_types=1);

namespace App\Module\City\Models;

use Database\Factories\CountryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $title
 * @property string $name
 * @property string $code
 */
final class Country extends Model
{
    use HasFactory;

    protected $table = 'countries';

    protected static function newFactory(): CountryFactory
    {
        return CountryFactory::new();
    }
}
