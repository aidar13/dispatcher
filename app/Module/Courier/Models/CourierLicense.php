<?php

declare(strict_types=1);

namespace App\Module\Courier\Models;

use Carbon\Carbon;
use Database\Factories\CourierLicenseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int|null $courier_id
 * @property string|null $identify_card_number
 * @property string|null $identify_card_issue_date
 * @property string|null $driver_license_number
 * @property string|null $driver_license_issue_date
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 */
final class CourierLicense extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'courier_licenses';

    protected static function newFactory(): CourierLicenseFactory
    {
        return CourierLicenseFactory::new();
    }
}
