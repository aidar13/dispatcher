<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Module\Order\DTO\InvoiceCargoDTO;
use App\Module\Order\Models\InvoiceCargo;
use App\Module\Take\Models\OrderTake;

final class CargoHelper
{
    public static function getType(float|null $depth, float|null $height, float|null $width, float|null $volume): int
    {
        $isSmallCargo = $depth <= InvoiceCargo::SMALL_CARGO_MAX_DEPTH &&
            $height <= InvoiceCargo::SMALL_CARGO_MAX_HEIGHT &&
            $width <= InvoiceCargo::SMALL_CARGO_MAX_WIDTH &&
            $volume <= InvoiceCargo::SMALL_CARGO_MAX_VOLUME;

        return $isSmallCargo
            ? InvoiceCargo::TYPE_SMALL_CARGO
            : InvoiceCargo::TYPE_OVERSIZE_CARGO;
    }

    public static function getTypeFromCargoDTO(InvoiceCargoDTO $DTO): int
    {
        $isSmallCargo = $DTO->depth <= InvoiceCargo::SMALL_CARGO_MAX_DEPTH &&
            $DTO->height <= InvoiceCargo::SMALL_CARGO_MAX_HEIGHT &&
            $DTO->width <= InvoiceCargo::SMALL_CARGO_MAX_WIDTH &&
            $DTO->volume <= InvoiceCargo::SMALL_CARGO_MAX_VOLUME;

        return $isSmallCargo
            ? InvoiceCargo::TYPE_SMALL_CARGO
            : InvoiceCargo::TYPE_OVERSIZE_CARGO;
    }

    public static function getVolumeInCubeMeterToWeightInKg(?float $volume): float
    {
        return NumberHelper::getRounded($volume * InvoiceCargo::VOLUME_IN_CUBE_METER_TO_WEIGHT_IN_KG_CONVERTER);
    }
}
