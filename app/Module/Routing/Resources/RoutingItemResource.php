<?php

declare(strict_types=1);

namespace App\Module\Routing\Resources;

use App\Module\Routing\DTO\RoutingItemDTO;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     @OA\Property(property="id",type="int",description="Id"),
 *     @OA\Property(property="invoiceId",type="int",description="invoiceId"),
 *     @OA\Property(property="orderId",type="int",description="orderId"),
 *     @OA\Property(property="type",type="int",description="type 1 - забор, 2 - доставка"),
 *     @OA\Property(property="invoiceNumber",type="string",description="invoiceNumber"),
 *     @OA\Property(property="orderNumber",type="string",description="orderNumber")
 * )
 * @property RoutingItemDTO $resource
 */
final class RoutingItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'            => $this->resource->id,
            'invoiceId'     => $this->resource->invoiceId,
            'orderId'       => $this->resource->orderId,
            'type'          => $this->resource->type,
            'invoiceNumber' => $this->resource->invoiceNumber,
            'orderNumber'   => $this->resource->orderNumber,
        ];
    }
}
