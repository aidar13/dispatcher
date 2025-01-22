<?php

declare(strict_types=1);

namespace App\Module\Planning\Models;

use App\Helpers\NumberHelper;
use App\Models\User;
use App\Module\Courier\Models\Courier;
use App\Module\DispatcherSector\Models\Sector;
use App\Module\DispatcherSector\Models\Wave;
use App\Module\Order\Models\FastDeliveryOrder;
use App\Module\Order\Models\Invoice;
use App\Module\Order\Models\InvoiceCargo;
use App\Module\Planning\Contracts\Queries\ContainerQuery;
use App\Module\Routing\Models\Routing;
use Database\Factories\ContainerFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int|null $user_id
 * @property int $sector_id
 * @property int $status_id
 * @property int $wave_id
 * @property int|null $courier_id
 * @property int|null $routing_id
 * @property string|null $doc_number
 * @property string $title
 * @property string $date
 * @property int $cargo_type
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property-read Sector $sector
 * @property-read Wave $wave
 * @property-read ContainerStatus $status
 * @property-read Courier|null $courier
 * @property-read Routing|null $routing
 * @property-read User $user
 * @property-read Collection|Invoice[] $invoices
 * @property-read FastDeliveryOrder|null $fastDeliveryOrder
 */
final class Container extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const SMALL_CARGO_PREFIX    = 'МГГ';
    public const OVERSIZE_CARGO_PREFIX = 'КГГ';

    public const SMALL_CARGO_ID           = 1;
    public const OVERSIZE_CARGO_ID        = 2;
    public const ONE_C_DOCUMENT_TYPE_NAME = 'Container';

    protected static function newFactory(): ContainerFactory
    {
        return ContainerFactory::new();
    }

    public function invoices(): HasManyThrough
    {
        return $this->hasManyThrough(
            Invoice::class,
            ContainerInvoice::class,
            'container_id',
            'id',
            'id',
            'invoice_id',
        )
            ->select('invoices.*', 'containers_invoices.position')
            ->leftJoin('receivers', 'receivers.id', '=', 'invoices.receiver_id')
            ->addSelect('receivers.latitude', 'receivers.longitude')
            ->orderBy('position');
    }

    public function sector(): BelongsTo
    {
        return $this->belongsTo(Sector::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function wave(): BelongsTo
    {
        return $this->belongsTo(Wave::class);
    }

    public function courier(): BelongsTo
    {
        return $this->belongsTo(Courier::class);
    }

    public function routing(): BelongsTo
    {
        return $this->belongsTo(Routing::class);
    }

    public function fastDeliveryOrder(): HasOne
    {
        return $this->hasOne(FastDeliveryOrder::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(ContainerStatus::class);
    }

    public function setSectorId(int $sectorId): void
    {
        $this->sector_id = $sectorId;
    }

    public function setWaveId(int $waveId): void
    {
        $this->wave_id = $waveId;
    }

    public function setDate(string $date): void
    {
        $this->date = $date;
    }

    public function setCargoType(int $cargoType): void
    {
        $this->cargo_type = $cargoType;
    }

    public function setTitle(): void
    {
        /** @var ContainerQuery $containerQuery */
        $containerQuery = app(ContainerQuery::class);

        $lastId    = (int)$containerQuery->getLastId()?->id;
        $cargoType = $this->cargo_type === InvoiceCargo::TYPE_SMALL_CARGO
            ? self::SMALL_CARGO_PREFIX
            : self::OVERSIZE_CARGO_PREFIX;

        $keys = [
            $this->sector?->name ?? 'unknown_sector',
            $this->wave?->title ?? 'unknown_wave',
            $this->date,
            $cargoType,
            ++$lastId
        ];

        $this->title = implode('-', $keys);
    }

    public function setUserId(int $userId): void
    {
        $this->user_id = $userId;
    }

    public function setCourierId(?int $courierId): void
    {
        $this->courier_id = $courierId;
    }

    public function setRoutingId(?int $routingId): void
    {
        $this->routing_id = $routingId;
    }

    public function getWeight(): float
    {
        $totalWeight = 0;

        foreach ($this->invoices as $invoice) {
            $totalWeight += $invoice->cargo?->weight;
        }

        return NumberHelper::getRounded($totalWeight);
    }

    public function getVolumeWeight(): float
    {
        $totalWeight = 0;

        foreach ($this->invoices as $invoice) {
            $totalWeight += $invoice->cargo?->volume_weight;
        }

        return NumberHelper::getRounded($totalWeight);
    }

    public function isReadyToCreateFastDelivery(): bool
    {
        if (
            $this->fastDeliveryOrder->type === FastDeliveryOrder::TYPE_YANDEX_DELIVERY &&
            $this->status_id !== ContainerStatus::ID_ASSEMBLED
        ) {
            return false;
        }

        return true;
    }
}
