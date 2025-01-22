<?php

declare(strict_types=1);

namespace App\Module\Take\Models;

use Database\Factories\OrderPeriodFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $from
 * @property string $to
 * @property string $title
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class OrderPeriod extends Model
{
    use HasFactory;

    protected $table = 'order_periods';

    const ID_BEFORE_LUNCH = 1;
    const TITLE_BEFORE_LUNCH = 'До обеда (8:00 - 12:00)';
    const ID_AFTER_LUNCH = 2;
    const TITLE_AFTER_LUNCH = 'После обеда (12:00 - 18:00)';

    protected static function newFactory(): OrderPeriodFactory
    {
        return OrderPeriodFactory::new();
    }
}
