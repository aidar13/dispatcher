<?php

declare(strict_types=1);

namespace App\Module\Status\Models;

use Database\Factories\StatusTypeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Module\Status\Models\StatusType
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property int $code
 * @property int $type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @method static StatusTypeFactory factory($count = null, $state = [])
 */
final class StatusType extends Model
{
    use HasFactory;
    use SoftDeletes;

    const TYPE_TAKE = 1;
    const TYPE_DELIVERY = 2;

    const ID_NOT_ASSIGNED = 1;
    const ID_ASSIGNED = 2;
    const ID_TAKEN = 3;
    const ID_CARGO_HANDLING = 4;
    const ID_TAKE_CANCELED = 5;
    const ID_DELIVERY_CREATED = 21;
    const ID_IN_DELIVERING = 22;
    const ID_DELIVERED = 23;
    const ID_PICKUP = 24;
    const ID_RECEIVER_CANCELED = 25;
    const ID_DATE_CHANGE = 26;
    const ID_CARGO_RETURNED = 27;
    const ID_RECEIVER_MISSING = 28;

    public const DELIVERY_DONE_STATUSES = [
        self::ID_CARGO_RETURNED,
        self::ID_DELIVERED,
        self::ID_TAKE_CANCELED,
    ];

    public const ORDER_TAKE_INCOMPLETED_STATUSES = [
        self::ID_NOT_ASSIGNED,
        self::ID_ASSIGNED,
        self::ID_TAKEN,
    ];

    public const TAKE_STATUS_NOT_ASSIGNED        = 'Не назначен на курьера';
    public const TAKE_STATUS_ASSIGNED_TO_COURIER = 'Назначен на курьера';
    public const TAKE_STATUS_TAKEN               = 'Забран курьером';
    public const TAKE_STATUS_CARGO_HANDLING      = 'Забор отгружен на склад';

    protected static function newFactory(): StatusTypeFactory
    {
        return StatusTypeFactory::new();
    }
}
