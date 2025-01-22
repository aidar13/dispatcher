<?php

declare(strict_types=1);

namespace App\Module\Courier\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $title
 */
final class CourierStatus extends Model
{
    protected $table = 'courier_statuses';

    public const ID_IN_CHECKUP = 1;
    public const ID_ACTIVE     = 2;
    public const ID_ARCHIVE    = 3;
    public const ID_REJECTED   = 4;
}
