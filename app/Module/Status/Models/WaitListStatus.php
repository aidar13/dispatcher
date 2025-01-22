<?php

declare(strict_types=1);

namespace App\Module\Status\Models;

use App\Models\User;
use App\Module\Order\Models\Invoice;
use App\Module\Order\Models\Order;
use Carbon\Carbon;
use Database\Factories\WaitListStatusFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property int $client_id
 * @property string $client_type
 * @property int $state_id
 * @property string|null $value
 * @property string|null $comment
 * @property int $code
 * @property int|null $parent_id
 * @property int $user_id
 * @property string|null $source
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read RefStatus $refStatus
 * @property-read User $user
 * @property-read Invoice|Order $client
 * @property-read Invoice $invoice
 * @property-read Order $order
 * @property-read WaitListStatus[]|Collection $child
 * @property-read WaitListStatus $parent
 *
 */
final class WaitListStatus extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const ID_IN_WORK_AT_CC = 47;
    public const ID_CONFIRMED     = 48;
    public const ID_DENIED        = 49;
    public const ID_CALL_DID_NOT_REACHED = 61;

    public const ID_DELIVERY_CLIENT_TYPE = 1;
    public const ID_TAKE_CLIENT_TYPE     = 2;

    public const KEY = 'wait_list_status';


    public static function newFactory(): WaitListStatusFactory
    {
        return WaitListStatusFactory::new();
    }

    public function client(): MorphTo
    {
        return $this->morphTo();
    }

    public function refStatus(): BelongsTo
    {
        return $this->belongsTo(RefStatus::class, 'code', 'code');
    }

    public function child(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function parent(): HasOne
    {
        return $this->hasOne(self::class, 'id', 'parent_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function invoice(): MorphTo
    {
        return $this->client()->where('client_type', Invoice::class);
    }

    public function order(): MorphTo
    {
        return $this->morphTo('client')->where('client_type', Order::class);
    }

    public function getClientTitle(): string
    {
        return match ($this->client_type) {
            Invoice::class => 'Доставка',
            Order::class   => 'Забор',
        };
    }

    public function getClientTypeId(): int
    {
        return match ($this->client_type) {
            Invoice::class => self::ID_DELIVERY_CLIENT_TYPE,
            Order::class   => self::ID_TAKE_CLIENT_TYPE,
        };
    }

    public function getStateName(): string
    {
        return match ($this->state_id) {
            self::ID_IN_WORK_AT_CC => 'В работе у КЦ',
            self::ID_CONFIRMED     => 'Подтвержден',
            self::ID_DENIED        => 'Отказано',
            self::ID_CALL_DID_NOT_REACHED => 'Не дозвон',
        };
    }

    /**
     * @psalm-suppress UndefinedMagicPropertyFetch
     */
    public function getNumber(): ?string
    {
        return match ($this->client_type) {
            Invoice::class => $this->client->invoice_number,
            Order::class   => $this->client->number,
        };
    }

    public function clientTypeIsInvoice(): bool
    {
        return $this->client_type === Invoice::class;
    }

    public function isStateWorkAtCC(): bool
    {
        return $this->state_id === self::ID_IN_WORK_AT_CC;
    }

    public function isStateConfirmed(): bool
    {
        return $this->state_id === self::ID_CONFIRMED;
    }

    public function isStateDenied(): bool
    {
        return $this->state_id === self::ID_DENIED;
    }
}
