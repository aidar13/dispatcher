<?php

declare(strict_types=1);

namespace App\Module\Order\Models;

use Database\Factories\SlaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $city_from
 * @property int $city_to
 * @property int $pickup
 * @property int $processing
 * @property int $transit
 * @property int $delivery
 * @property int|null $shipment_type_id
 * @method static find(int $id)
 */
final class Sla extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'sla_sla';

    public const EXTRA_HOURS_FOR_CLIENT = 12;

    protected static function newFactory(): SlaFactory
    {
        return SlaFactory::new();
    }

    public function getDeadLineHours(): int
    {
        return $this->pickup + $this->processing + $this->transit + $this->delivery;
    }

    public function getClientSla(?Carbon $date): ?Carbon
    {
        if (!$date) {
            return null;
        }

        return Carbon::parse($date)->addHours($this->getDeadLineHours() + self::EXTRA_HOURS_FOR_CLIENT);
    }

    public function getSla(?Carbon $date, bool $isSelfDelivery, bool $isSelfPickup): ?Carbon
    {
        if (!$date) {
            return null;
        }

        $slaHours = $this->processing + $this->transit;

        $isSelfDelivery ?: $slaHours += $this->pickup;
        $isSelfPickup ?: $slaHours += $this->delivery;

        return Carbon::parse($date)->addHours($slaHours);
    }
}
