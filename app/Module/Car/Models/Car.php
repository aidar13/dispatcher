<?php

declare(strict_types=1);

namespace App\Module\Car\Models;

use App\Module\Company\Models\Company;
use Database\Factories\CarFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $status_id
 * @property int $company_id
 * @property int $vehicle_type_id
 * @property string|null $code_1C
 * @property string $number
 * @property string $model
 * @property int $cubature
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property-read CarType|null $carType
 * @property-read Company|null $company
 * @property-read Collection|CarOccupancy[] $carOccupancies
 */
final class Car extends Model
{
    use HasFactory;
    use SoftDeletes;

    const DOCUMENT_PATH = 'cars_documents';

    const TRUCK_MAX_STOPS_AMOUNT = 25;
    const PASSANGER_MAX_STOPS_AMOUNT = 30;

    protected static function newFactory(): CarFactory
    {
        return CarFactory::new();
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function carType(): BelongsTo
    {
        return $this->belongsTo(CarType::class, 'vehicle_type_id');
    }

    public function carOccupancies(): HasMany
    {
        return $this->hasMany(CarOccupancy::class);
    }
}
