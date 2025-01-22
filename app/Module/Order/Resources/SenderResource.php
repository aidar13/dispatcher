<?php

namespace App\Module\Order\Resources;

use App\Http\Resources\BaseJsonResource;
use App\Module\DispatcherSector\Resources\SectorResource;
use App\Module\Order\Models\Sender;

/**
 * @OA\Schema(
 *     @OA\Property(property="id",type="int",),
 *     @OA\Property(property="fullAddress", type="string", example="508147, Амурская область, город Дмитров, пер. Космонавтов, 78"),
 *     @OA\Property(property="fullName", type="string", example="Чингиз"),
 *     @OA\Property(property="phone", type="int", example="77771231212"),
 *     @OA\Property(property="additionalPhone", type="int", example="77771231212"),
 *     @OA\Property(property="latitude", type="string", example="52.307581"),
 *     @OA\Property(property="longitude", type="string", example="76.307581"),
 *     @OA\Property(property="comment", type="string", example="Обращаться по номеру +777777777"),
 *     @OA\Property(property="selfDelivery",type="bool",example="Самопривоз"),
 *     @OA\Property(property="cityName", type="string", description="Название города", example="Алматы"),
 *     @OA\Property(
 *         property="sector",
 *         ref="#/components/schemas/SectorShowResource"
 *     ),
 * )
 * @property Sender $resource
 */
final class SenderResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        return [
            'id'              => $this->resource->id,
            'fullName'        => $this->resource->full_name,
            'fullAddress'     => $this->resource->full_address,
            'phone'           => $this->resource->phone,
            'additionalPhone' => $this->resource->additional_phone,
            'latitude'        => $this->resource->latitude,
            'longitude'       => $this->resource->longitude,
            'comment'         => $this->resource->comment,
            'cityName'        => $this->resource?->city?->name,
            'selfDelivery'    => (bool)$this->resource->warehouse_id,
            'sector'          => new SectorResource($this->resource->sector, false),
        ];
    }
}
