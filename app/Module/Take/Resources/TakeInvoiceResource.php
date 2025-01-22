<?php

declare(strict_types=1);

namespace App\Module\Take\Resources;

use App\Http\Resources\BaseJsonResource;
use App\Module\Take\Models\OrderTake;

/**
 * @OA\Schema (
 *     @OA\Property(property="takeId", type="integer", example="1"),
 *     @OA\Property(property="invoiceId", type="integer", example="1"),
 *     @OA\Property(property="invoiceNumber", type="string", example="SP000001"),
 *     @OA\Property(property="receiverCity", type="string", example="Алматы"),
 *     @OA\Property(property="sizeType", type="string", example="XS"),
 *     @OA\Property(property="statusName", type="string", example="Забран курьером"),
 *     @OA\Property(property="statusId", type="int", example="1"),
 * )
 *
 * @property OrderTake $resource
 */
final class TakeInvoiceResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        return [
            'takeId'        => $this->resource->id,
            'invoiceId'     => $this->resource?->invoice?->id,
            'invoiceNumber' => $this->resource?->invoice?->invoice_number,
            'receiverCity'  => $this->resource?->invoice?->receiver?->city?->name,
            'sizeType'      => $this->resource?->cargo?->size_type,
            'statusName'    => $this->resource?->status?->title,
            'statusId'      => $this->resource?->status?->id,
        ];
    }
}
