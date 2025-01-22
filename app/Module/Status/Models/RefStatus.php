<?php

declare(strict_types=1);

namespace App\Module\Status\Models;

use App\Traits\HasCrossDatabaseConnection;
use Database\Factories\RefStatusFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RefStatus
 *
 * @property int $id
 * @property string $name
 * @property int $code
 * @property int $order
 * @property string $comment
 * @property bool $is_visible
 * @property bool $is_active
 * @property int $wait_list_type
 * @property string $short_name
 * @property mixed $created_at
 */
final class RefStatus extends Model
{
    use HasCrossDatabaseConnection;
    use HasFactory;

    protected $table = 'ref_statuses';

    public function __construct(array $attributes = [])
    {
        $this->connection = !app()->runningUnitTests() ? 'mysql_cabinet' : 'sqlite';
        $this->table      = $this->setTableName($this->connection, $this->table);

        parent::__construct($attributes);
    }

    public const CODE_CREATE                       = 201;
    public const CODE_ASSIGNED_TO_COURIER          = 202;
    public const CODE_CARGO_PICKED_UP              = 203;
    public const CODE_PICKUP_CANCELED              = 204;
    public const CODE_CARGO_HANDLING               = 225;
    public const CODE_CARGO_AWAIT_SHIPMENT         = 205;
    public const CODE_CARGO_IN_TRANSIT             = 206;
    public const CODE_CARGO_ARRIVED_CITY           = 208;
    public const CODE_DELIVERY_IN_PROGRESS         = 210;
    public const CODE_DELIVERED                    = 211;
    public const CODE_CARGO_RETURNED               = 221;
    public const CODE_DELIVERY_CANCELED            = 212;
    public const CODE_CHANGED_TAKE_DATE            = 213;
    public const CODE_RETURN_DELIVERY              = 220;
    public const CODE_COURIER_RETURN_DELIVERY      = 230;
    public const CODE_PAID                         = 231;
    public const CODE_APPROXIMATE_DELIVERY_TO_CITY = 232;
    public const CODE_RESTORED                     = 233;
    public const CODE_CHANGED_DELIVERY_DATE        = 234;

    // 1C Статусы
    public const CODE_ORDER_CANCELLED = 503;

    // Статусы листа ожидания
    public const CODE_INCORRECT_ADDRESS            = 301;
    public const CODE_CANCEL_RECEIVE               = 302;
    public const CODE_MISSING_RECEIVER             = 303;
    public const CODE_FORWARDING_ON_DELIVERY       = 304;
    public const CODE_RETURN_TO_SENDER             = 306;
    public const CODE_HOLD_BY_RECEIVER_REQUEST     = 307;
    public const CODE_CAR_BREAKDOWN_ON_DELIVERY    = 308;
    public const CODE_DID_NOT_DELIVERY_UNTIL_18    = 309;
    public const CODE_RECEIVER_NOT_ANSWERING_CALLS = 310;
    public const CODE_RECEIVER_PHONE_IS_INCORRECT  = 316;
    public const CODE_ON_HOLD                      = 332;
    public const CODE_WAITING_FOR_WL_CONFIRMATION  = 333;
    public const CODE_DAMAGE_OR_DIVERGENCE         = 343;

    // ПВЗ Статусы
    public const CODE_DISTRIBUTION_CENTER_DELIVERY_IN_PROGRESS = 701;
    public const CODE_DISTRIBUTION_CENTER_DELIVERED            = 702;
    public const CODE_DISTRIBUTION_CENTER_TAKEN_BY_RECEIVER    = 703;

    public const ID_CREATED                       = 1;
    public const ID_PICKED_UP                     = 3;
    public const ID_CANCELLED                     = 4;
    public const ID_CARGO_HANDLING                = 5;
    public const ID_CARGO_AWAIT_SHIPMENT          = 6;
    public const ID_CARGO_IN_TRANSIT              = 7;
    public const ID_CARGO_ARRIVED_CITY            = 8;
    public const ID_DELIVERY_IN_PROGRESS          = 9;
    public const ID_DELIVERED                     = 10;
    public const ID_DISTRIBUTION_CENTER_DELIVERED = 13;
    public const ID_CHANGE_TAKE_DATE              = 16;
    public const ID_COURIER_RETURN_DELIVERY       = 23;
    public const ID_INCORRECT_ADDRESS             = 24;
    public const ID_MISSING_RECEIVER              = 26;
    public const ID_FORWARDING_ON_DELIVERY        = 27;
    public const ID_CODE_RETURN_TO_SENDER         = 29;
    public const ID_HOLD_BY_RECEIVER_REQUEST      = 30;
    public const ID_CAR_BREAKDOWN_ON_DELIVERY     = 31;
    public const ID_DID_NOT_DELIVERY_UNTIL_18     = 32;
    public const ID_RECEIVER_NOT_ANSWERING_CALLS  = 33;
    public const ID_RECEIVER_PHONE_IS_INCORRECT   = 37;
    public const ID_ORDER_CANCELED                = 51;
    public const ID_PAID                          = 52;
    public const ID_PREDICT_DELIVERY              = 53;
    public const ID_APPROXIMATE_DELIVERY_TO_CITY  = 53;
    public const ID_ON_HOLD                       = 56;
    public const ID_WAITING_FOR_WL_CONFIRMATION   = 57;
    public const ID_CHANGED_DELIVERY_DATE         = 61;

    public const WAVE_ASSIGNABLE_TO_INVOICE_STATUSES = [
        self::CODE_APPROXIMATE_DELIVERY_TO_CITY,
        self::CODE_CARGO_ARRIVED_CITY,
        self::CODE_CARGO_AWAIT_SHIPMENT
    ];

    public const WAIT_LIST_STATUSES = [
        self::CODE_CHANGED_TAKE_DATE            => self::ID_CHANGE_TAKE_DATE,
        self::CODE_INCORRECT_ADDRESS            => self::ID_INCORRECT_ADDRESS,
        self::CODE_MISSING_RECEIVER             => self::ID_MISSING_RECEIVER,
        self::CODE_FORWARDING_ON_DELIVERY       => self::ID_FORWARDING_ON_DELIVERY,
        self::CODE_RETURN_TO_SENDER             => self::ID_CODE_RETURN_TO_SENDER,
        self::CODE_HOLD_BY_RECEIVER_REQUEST     => self::ID_HOLD_BY_RECEIVER_REQUEST,
        self::CODE_CAR_BREAKDOWN_ON_DELIVERY    => self::ID_CAR_BREAKDOWN_ON_DELIVERY,
        self::CODE_DID_NOT_DELIVERY_UNTIL_18    => self::ID_DID_NOT_DELIVERY_UNTIL_18,
        self::CODE_RECEIVER_NOT_ANSWERING_CALLS => self::ID_RECEIVER_NOT_ANSWERING_CALLS,
        self::CODE_ON_HOLD                      => self::ID_ON_HOLD,
    ];

    public const TAKE_CANCEL_STATUSES = [
        self::CODE_PICKUP_CANCELED,
        self::CODE_ORDER_CANCELLED,
    ];

    public const DELIVERY_CANCEL_STATUSES = [
        self::CODE_CANCEL_RECEIVE,
        self::CODE_DELIVERY_CANCELED,
        self::CODE_ORDER_CANCELLED,
    ];

    public const DELIVERY_RETURNED_STATUSES = [
        self::CODE_COURIER_RETURN_DELIVERY
    ];

    public const WAITING_LIST_CODES = [
        self::CODE_INCORRECT_ADDRESS,
        self::CODE_MISSING_RECEIVER,
        self::CODE_FORWARDING_ON_DELIVERY,
        self::CODE_RETURN_TO_SENDER,
        self::CODE_HOLD_BY_RECEIVER_REQUEST,
        self::CODE_CAR_BREAKDOWN_ON_DELIVERY,
        self::CODE_DID_NOT_DELIVERY_UNTIL_18,
        self::CODE_RECEIVER_NOT_ANSWERING_CALLS,
        self::CODE_CHANGED_TAKE_DATE
    ];

    public const WAIT_LIST_STATUS_CODES_DELIVERY = [
        self::CODE_CAR_BREAKDOWN_ON_DELIVERY,
        self::CODE_FORWARDING_ON_DELIVERY,
        self::CODE_DID_NOT_DELIVERY_UNTIL_18,
        self::CODE_RECEIVER_NOT_ANSWERING_CALLS,
        self::CODE_RECEIVER_PHONE_IS_INCORRECT,
        self::CODE_CHANGED_DELIVERY_DATE,
    ];

    protected static function newFactory(): RefStatusFactory
    {
        return RefStatusFactory::new();
    }

    public static function statusCodeInWaitingList(int $status): bool
    {
        return in_array($status, self::WAITING_LIST_CODES);
    }

    public static function getNameByCode($code)
    {
        return (static::where('code', $code)->firstOrFail())->name;
    }
}
