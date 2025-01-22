<?php

declare(strict_types=1);

namespace App\Module\Order\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $title
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class ShipmentType extends Model
{
    const ID_CAR      = 1;
    const TITLE_CAR   = 'Авто';
    const ID_PLANE    = 2;
    const TITLE_PLANE = 'Авиа';
}
