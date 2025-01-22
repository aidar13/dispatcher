<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Resources\Delivery;

use App\Http\Resources\BaseJsonResource;
use App\Module\Order\Models\Receiver;

/**
 * @OA\Schema(
 *     @OA\Property(property="id", type="integer", description="ID получателя", example=1),
 *     @OA\Property(property="fullName", type="string", description="Имя получателя", example="Азнабакиев Абдрахман"),
 *     @OA\Property(property="address", type="string", description="Адрес получателя", example="Толе би 101"),
 *     @OA\Property(property="office", type="string", description="Офис получателя", example="1"),
 *     @OA\Property(property="house", type="string", description="Дом получателя", example="2"),
 *     @OA\Property(property="comment", type="string", description="Коммент по доставке", example="Хрупкий товар"),
 *     @OA\Property(property="phone", type="string", description="Номер телефона", example="87477777777"),
 *     @OA\Property(property="additionalPhone", type="string", description="Доп номер телефона", example="87477777777"),
 *     @OA\Property(property="latitude", type="string", description="Широта", example="78.183604"),
 *     @OA\Property(property="longitude", type="string", description="Долгота", example="69.353267"),
 * )
 * @property Receiver $resource
 */
final class CourierDeliveryReceiverResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        return [
            'id'              => $this->resource->id,
            'fullName'        => $this->resource?->full_name,
            'address'         => $this->resource?->full_address,
            'office'          => $this->resource?->office,
            'house'           => $this->resource?->house,
            'comment'         => $this->resource?->comment,
            'phone'           => $this->resource?->phone,
            'additionalPhone' => $this->resource?->additional_phone,
            'latitude'        => $this->resource?->latitude != '' ? $this->resource?->latitude : null,
            'longitude'       => $this->resource?->longitude != '' ? $this->resource?->longitude : null,
        ];
    }
}
