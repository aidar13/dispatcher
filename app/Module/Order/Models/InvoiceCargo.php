<?php

namespace App\Module\Order\Models;

use App\Module\Inventory\Models\Inventory;
use Carbon\Carbon;
use Database\Factories\InvoiceCargoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $invoice_id
 * @property string $cargo_name
 * @property string|null $product_name
 * @property string|null $pack_code
 * @property string|null $size_type
 * @property int|null $places
 * @property float|null $weight
 * @property float|null $volume
 * @property float|null $volume_weight
 * @property float|null $width
 * @property float|null $depth
 * @property float|null $height
 * @property int|null $cod_payment
 * @property string|null $annotation
 * @property Carbon $created_at
 * @property-read int $cubature
 * @property-read Invoice $invoice
 */
final class InvoiceCargo extends Model
{
    use HasFactory;

    protected $table = 'invoice_cargo';

    public const SMALL_CARGO_MAX_DEPTH  = 60;
    public const SMALL_CARGO_MAX_WIDTH  = 60;
    public const SMALL_CARGO_MAX_HEIGHT = 60;
    public const SMALL_CARGO_MAX_VOLUME = 0.15;

    public const TYPE_SMALL_CARGO    = 1;
    public const TYPE_OVERSIZE_CARGO = 2;

    public const DEFAULT_VOLUME_CONVERTER                       = 5000;
    public const VOLUME_IN_CUBE_METER_TO_WEIGHT_IN_KG_CONVERTER = 200;

    protected static function newFactory(): InvoiceCargoFactory
    {
        return InvoiceCargoFactory::new();
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function getCubatureAttribute(): int
    {
        if ($this->width === null || $this->height === null || $this->depth === null) {
            return 0;
        }

        $volumeInCentimeters = $this->width * $this->depth * $this->height;
        $convertedInMeters   = $volumeInCentimeters / self::DEFAULT_VOLUME_CONVERTER;

        return (int)round($convertedInMeters);
    }

    public function getWriteOffItemsForSparkDelivery(): array
    {
        return match ($this->size_type) {
            Inventory::SPARK_BOX_L  => [['inventoryItemId' => Inventory::SPARK_BOX_L_INVENTORY_ITEM_ID, 'amount' => 1]],
            Inventory::SPARK_BOX_M  => [
                ['inventoryItemId' => Inventory::SPARK_BOX_M_INVENTORY_ITEM_ID, 'amount' => 1],
                ['inventoryItemId' => Inventory::AIR_BUBBLE_INVENTORY_ITEM_ID, 'amount' => Inventory::AMOUNT_AIR_BUBBLE_FOR_SPARK_BOX_M],
            ],
            Inventory::SPARK_BOX_S  => [
                ['inventoryItemId' => Inventory::SPARK_BOX_S_INVENTORY_ITEM_ID, 'amount' => 1],
                ['inventoryItemId' => Inventory::AIR_BUBBLE_INVENTORY_ITEM_ID, 'amount' => Inventory::AMOUNT_AIR_BUBBLE_FOR_SPARK_BOX_S],
            ],
            Inventory::SPARK_BOX_XS => [
                ['inventoryItemId' => Inventory::SPARK_BOX_XS_INVENTORY_ITEM_ID, 'amount' => 1],
                ['inventoryItemId' => Inventory::AIR_BUBBLE_INVENTORY_ITEM_ID, 'amount' => Inventory::AMOUNT_AIR_BUBBLE_FOR_SPARK_BOX_XS],
            ],
            Inventory::ENVELOPE     => [['inventoryItemId' => Inventory::ENVELOPE_INVENTORY_ITEM_ID, 'amount' => 1]],
            Inventory::PACKAGE      => [['inventoryItemId' => Inventory::PACKAGE_INVENTORY_ITEM_ID, 'amount' => 1]],
        };
    }
}
