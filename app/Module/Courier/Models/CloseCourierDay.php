<?php

declare(strict_types=1);

namespace App\Module\Courier\Models;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $courier_id
 * @property int $user_id
 * @property string $date
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property-read Courier $courier
 * @property-read User $user
 */

class CloseCourierDay extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'close_courier_day';

    protected $fillable = [
        'courier_id',
        'user_id',
        'date'
    ];

    public function courier(): BelongsTo
    {
        return $this->belongsTo(Courier::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
