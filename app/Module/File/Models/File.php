<?php

declare(strict_types=1);

namespace App\Module\File\Models;

use App\Models\User;
use App\Module\Car\Models\Car;
use App\Module\Courier\Models\Courier;
use App\Module\Order\Models\Invoice;
use App\Module\Order\Models\Order;
use Database\Factories\FileFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property int $type
 * @property string $path
 * @property string $original_name
 * @property int $client_id
 * @property int $user_id
 * @property string $client_type
 * @property string $uuid_hash
 * @property User $user
 * @property Carbon|null $updated_at
 * @property Carbon|null $created_at
 * @property Carbon|null $deleted_at
 */
final class File extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const TYPE_INVOICE_SCAN                = 1;
    public const TYPE_DELIVERY_APPROVE            = 5;
    public const TYPE_COURIER_IDENTIFICATION_CARD = 10;
    public const TYPE_COURIER_DRIVER_LICENSE      = 11;
    public const TYPE_CAR_TECHNICAL_PASSPORT      = 12;
    public const TYPE_COURIER_SHORTCOMING_REPORT  = 19;
    public const TYPE_COURIER_SHORTCOMING_PRODUCT = 20;
    public const TYPE_COURIER_PARKING_CHECK       = 22;
    public const TYPE_COURIER_ROAD_CHECK          = 23;

    public const DEFAULT_PATH = 'upload_dispatcher';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function newFactory(): FileFactory
    {
        return FileFactory::new();
    }

    /**
     * @psalm-suppress UndefinedInterfaceMethod
     */
    public function getUrl(): string
    {
        return Storage::disk('s3')->url($this->path);
    }

    public static function getClientTypeByClientType(string $clientType): string
    {
        return match ($clientType) {
            'App\Models\CourierApp\Courier'              => Courier::class,
            'App\Module\CourierApp\Models\Eloquent\Car'  => Car::class,
            'App\Module\Order\Models\Order'              => Order::class,
            'App\Module\Order\Models\OrderLogisticsInfo' => Invoice::class,
            default                                      => self::class
        };
    }

    public static function getClientType(int $type): string
    {
        return match ($type) {
            self::TYPE_COURIER_IDENTIFICATION_CARD, self::TYPE_COURIER_DRIVER_LICENSE => Courier::class,
            self::TYPE_CAR_TECHNICAL_PASSPORT      => Car::class,
            default                                => self::class
        };
    }

    public static function getPath(int $type): string
    {
        return match ($type) {
            self::TYPE_COURIER_IDENTIFICATION_CARD, self::TYPE_COURIER_DRIVER_LICENSE => Courier::DOCUMENT_PATH,
            self::TYPE_CAR_TECHNICAL_PASSPORT      => Car::DOCUMENT_PATH,
            default                                => self::DEFAULT_PATH
        };
    }

    public function setUuidHash(): void
    {
        $this->uuid_hash = now()->timestamp . md5($this->id . $this->original_name);
    }
}
