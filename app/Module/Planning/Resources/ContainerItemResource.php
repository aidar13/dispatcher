<?php

declare(strict_types=1);

namespace App\Module\Planning\Resources;

use App\Module\Planning\Models\Container;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Название контейнера"),
 *     @OA\Property(property="sectorId", type="integer", example=1),
 *     @OA\Property(property="waveId", type="integer", example=1),
 *     @OA\Property(property="cargoType", type="integer", example=1),
 *     @OA\Property(property="date", type="date", example="2024-02-02"),
 *     @OA\Property(property="invoiceQuantity", type="integer"),
 *     @OA\Property(property="sectorName", type="string"),
 *     @OA\Property(property="weight", type="integer"),
 *     @OA\Property(property="volumeWeight", type="integer"),
 *     @OA\Property(property="createdAt", type="string"),
 *     @OA\Property(property="courierId", type="integer"),
 *     @OA\Property(property="courierName", type="string"),
 *     @OA\Property(property="userId", type="integer"),
 *     @OA\Property(property="userName", type="string"),
 *     @OA\Property(property="provider", type="string"),
 *     @OA\Property(property="invoices", type="array", @OA\Items(ref="#/components/schemas/ContainerInvoicesResource")),
 *     @OA\Property(property="status", ref="#/components/schemas/ContainerStatusResource"),
 * )
 * @property Container $resource
 */
final class ContainerItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'              => $this->resource->id,
            'title'           => $this->resource->title,
            'invoiceQuantity' => $this->resource->invoices->count(),
            'sectorId'        => $this->resource->sector_id,
            'sectorName'      => $this->resource->sector->name,
            'weight'          => $this->resource->getWeight(),
            'volumeWeight'    => $this->resource->getVolumeWeight(),
            'createdAt'       => $this->resource->created_at->format('Y-m-d H:i:s'),
            'courierId'       => $this->resource->courier_id,
            'courierName'     => $this->resource->fastDeliveryOrder
                ? $this->resource->fastDeliveryOrder?->getCourier()
                : $this->resource->courier?->full_name,
            'userId'          => $this->resource->user_id,
            'userName'        => $this->resource->user?->name,
            'date'            => $this->resource->date,
            'waveId'          => $this->resource->wave_id,
            'cargoType'       => $this->resource->cargo_type,
            'provider'        => $this->resource->fastDeliveryOrder?->getProviderName(),
            'invoices'        => ContainerInvoicesResource::collection($this->resource->invoices),
            'status'          => new ContainerStatusResource($this->resource->status),
        ];
    }
}
