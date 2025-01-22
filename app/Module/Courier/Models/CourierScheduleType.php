<?php

declare(strict_types=1);

namespace App\Module\Courier\Models;

use Database\Factories\CourierScheduleTypeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $title
 * @property string $work_time_from
 * @property string $work_time_until
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read int $shift_id
 * @property-read string $shift
 */
final class CourierScheduleType extends Model
{
    use HasFactory;

    public const ID_FIRST_WAVE  = 1;
    public const ID_SECOND_WAVE = 2;
    public const ID_THIRD_WAVE  = 3;

    public const ID_ON_SHIFT  = 1;
    public const ID_OUT_SHIFT = 2;
    public const ON_SHIFT     = 'На смене';
    public const OUT_SHIFT    = 'Завершил смену';

    protected static function newFactory(): CourierScheduleTypeFactory
    {
        return CourierScheduleTypeFactory::new();
    }

    public function getShiftIdAttribute(): int
    {
        $currentDate = Carbon::now();
        return $currentDate->between($this->work_time_from, $this->work_time_until) ?
            self::ID_ON_SHIFT :
            self::ID_OUT_SHIFT;
    }

    public function getShiftAttribute(): string
    {
        $currentDate = Carbon::now();
        return $currentDate->between($this->work_time_from, $this->work_time_until) ?
            self::ON_SHIFT :
            self::OUT_SHIFT;
    }
}
