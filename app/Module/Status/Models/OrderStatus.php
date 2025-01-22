<?php

declare(strict_types=1);

namespace App\Module\Status\Models;

use App\Module\Order\Models\Invoice;
use Database\Factories\OrderStatusFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $invoice_id
 * @property string $invoice_number
 * @property int $order_id
 * @property int $code
 * @property string $title
 * @property string|null $comment
 * @property int|null $source_id
 * @property int|null $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Invoice $invoice
 */
final class OrderStatus extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'order_statuses';

    public const MINUTES_TO_AVOID_WAIT_LIST_DUPLICATION = 30;

    protected static function newFactory(): OrderStatusFactory
    {
        return OrderStatusFactory::new();
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function canCreateWaitListStatus(int $code): bool
    {
        return $this->equalsCode($code) && $this->isCreatedWithinPeriod(self::MINUTES_TO_AVOID_WAIT_LIST_DUPLICATION);
    }

    public function equalsCode(int $code): bool
    {
        return $this->code === $code;
    }

    public function isCreatedWithinPeriod(int $minutes): bool
    {
        return now()->diffInMinutes($this->created_at) <= $minutes;
    }
}
