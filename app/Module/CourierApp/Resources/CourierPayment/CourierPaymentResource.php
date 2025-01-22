<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Resources\CourierPayment;

use App\Module\CourierApp\Models\CourierPayment;
use App\Module\File\Resources\FileResource;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     @OA\Property(property="id", type="integer", description="ID"),
 *     @OA\Property(property="courierId", type="integer", description="ID курьера"),
 *     @OA\Property(property="userId", type="integer", description="ID пользователя"),
 *     @OA\Property(property="userEmail", type="string", description="Почта пользователя"),
 *     @OA\Property(property="clientId", type="integer", description="ID клиента"),
 *     @OA\Property(property="clientType", type="string", description="Тип клиента"),
 *     @OA\Property(property="type", type="string", description="Тип Id"),
 *     @OA\Property(property="typeName", type="string", description="Название типа"),
 *     @OA\Property(property="cost", type="integer", description="Общая стоимость"),
 *     @OA\Property(property="checks", type="object", ref="#/components/schemas/FileResource"),
 * )
 *
 * @property CourierPayment $resource
 */
final class CourierPaymentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'            => $this->resource->id,
            'courierId'     => $this->resource->courier_id,
            'userId'        => $this->resource->user_id,
            'userEmail'     => $this->resource->user?->email,
            'clientId'      => $this->resource->client_id,
            'clientType'    => $this->resource->client_type,
            'type'          => $this->resource->type,
            'typeName'      => $this->resource->getTypeName(),
            'cost'          => $this->resource->getCost(),
            'files'         => FileResource::collection($this->resource->getFiles()),
        ];
    }
}
