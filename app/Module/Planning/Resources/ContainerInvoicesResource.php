<?php

declare(strict_types=1);

namespace App\Module\Planning\Resources;

use App\Http\Resources\BaseJsonResource;
use App\Module\Delivery\Models\Delivery;
use App\Module\Order\Models\Invoice;
use App\Module\Status\Resources\StatusTypeResource;

/**
 * @OA\Schema(
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="invoiceNumber", type="string", description="Номер накладной"),
 *     @OA\Property(property="weight", type="integer", description="Физ вес"),
 *     @OA\Property(property="volumeWeight", type="integer", description="Объемный вес"),
 *     @OA\Property(property="createdAt", type="string", format="date-time", description="Дата создания"),
 *     @OA\Property(property="deliveryDate", type="string", format="date-time", description="Дата доставки"),
 *     @OA\Property(property="deliveryStatus", ref="#/components/schemas/StatusTypeResource", description="Статус доставки"),
 * )
 *
 * @property Invoice $resource
 */
final class ContainerInvoicesResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        /** @var Delivery $delivery */
        $delivery = $this->resource->deliveries->last();
        return [
            'id'             => $this->resource->id,
            'invoiceNumber'  => $this->resource->invoice_number,
            'weight'         => $this->resource->cargo?->weight,
            'volumeWeight'   => $this->resource->cargo?->volume_weight,
            'createdAt'      => $this->resource->created_at->format('Y-m-d H:i:s'),
            'deliveryDate'   => $delivery?->delivered_at,
            'deliveryStatus' => new StatusTypeResource($delivery?->status),
        ];
    }
}
