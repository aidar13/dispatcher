<?php

declare(strict_types=1);

namespace App\Module\Courier\Models;

use Database\Factories\CourierScheduleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $courier_id
 * @property int $weekday
 * @property string $work_time_from
 * @property string $work_time_until
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
final class CourierSchedule extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected static function newFactory(): CourierScheduleFactory
    {
        return CourierScheduleFactory::new();
    }
}
