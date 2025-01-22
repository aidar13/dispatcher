<?php

declare(strict_types=1);

namespace App\Module\Order\Models;

use App\Traits\HasCrossDatabaseConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $name
 * @property string $value
 * @property int $active
 * @property string $code
 * @property int $id
 * @property int $is_billingable
 */
final class AdditionalServiceType extends Model
{
    use SoftDeletes;
    use HasCrossDatabaseConnection;

    public const ID_DELIVERY          = 1;
    public const ID_LOADER            = 2;
    public const ID_WOOD_BOX          = 3;
    public const ID_SOFT_PACKAGE      = 4;
    public const ID_PALLET            = 5;
    public const ID_GRID              = 6;
    public const ID_MANIPULATOR       = 7;
    public const ID_CRANE             = 8;
    public const ID_CAR               = 9;
    public const ID_HYDRAULIC_TROLLEY = 10;
    public const ID_RISE_TO_THE_FLOOR = 11;

    protected $table = 'additional_service_types';
}
