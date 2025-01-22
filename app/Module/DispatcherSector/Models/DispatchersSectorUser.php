<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Models;

use App\Models\User;
use Database\Factories\DispatchersSectorUserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $dispatcher_sector_id
 * @property int $user_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read DispatcherSector $dispatcherSector
 * @property-read User $user
 */
final class DispatchersSectorUser extends Model
{
    use HasFactory;

    protected $table = 'dispatcher_sector_users';

    protected static function newFactory(): DispatchersSectorUserFactory
    {
        return DispatchersSectorUserFactory::new();
    }

    public function dispatcherSector(): BelongsTo
    {
        return $this->belongsTo(DispatcherSector::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
