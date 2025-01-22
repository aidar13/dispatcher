<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Resources\OrderTake;

use App\Helpers\NumberHelper;
use App\Http\Resources\BaseJsonResource;
use App\Module\Order\Models\AdditionalServiceType;
use App\Module\Take\Models\OrderTake;

/**
 * @OA\Schema (
 *     @OA\Property(property="takeId", type="integer", example=1),
 *     @OA\Property(property="places", type="integer", example=1),
 *     @OA\Property(property="weight", type="integer", example=1),
 *     @OA\Property(property="invoiceId", type="integer", example=1),
 *     @OA\Property(property="invoiceNumber", type="string", example="SP000001"),
 *     @OA\Property(property="dopInvoiceNumber", type="string", example="400274283444000"),
 *     @OA\Property(property="sizeType", type="string", example="M", description="Размер коробки"),
 *     @OA\Property(property="productName", type="string", example="Наименование товара", description="Наименование товара"),
 *     @OA\Property(property="receiverName", type="string", example="Akame500", description="Имя получателя"),
 *     @OA\Property(property="receiverAddress", type="string", example="Толе Би 101", description="Адрес получателя"),
 *     @OA\Property(property="hasSoftPackage", type="bool", description="Есть мягкая упаковка"),
 *     @OA\Property(property="direction", type="string", description="Направление"),
 * )
 *
 * @property OrderTake $resource
 */
final class CourierTakeInvoiceResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        return [
            'takeId'           => $this->resource->id,
            'places'           => $this->resource->places,
            'weight'           => NumberHelper::getRounded($this->resource->weight),
            'invoiceId'        => $this->resource->invoice?->id,
            'invoiceNumber'    => $this->resource->invoice?->invoice_number,
            'dopInvoiceNumber' => $this->resource->invoice?->dop_invoice_number,
            'sizeType'         => $this->resource->invoice?->cargo?->size_type,
            'productName'      => $this->resource->invoice?->cargo?->product_name,
            'receiverName'     => $this->resource->invoice?->receiver?->full_name,
            'receiverAddress'  => $this->resource->invoice?->receiver?->full_address,
            'hasSoftPackage'   => $this->resource->invoice?->hasAdditionalServiceValueByTypeId(AdditionalServiceType::ID_SOFT_PACKAGE),
            'direction'        => $this->resource->invoice?->getDirection(),
        ];
    }
}
