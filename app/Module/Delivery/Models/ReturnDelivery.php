<?php

declare(strict_types=1);

namespace App\Module\Delivery\Models;

use Database\Factories\ReturnDeliveryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $invoice_id
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
final class ReturnDelivery extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'return_deliveries';


    protected static function newFactory(): ReturnDeliveryFactory
    {
        return ReturnDeliveryFactory::new();
    }
}
