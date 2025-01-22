<?php

declare(strict_types=1);

namespace App\Module\Status\Resources;

use App\Http\Resources\BaseJsonResource;
use App\Module\Status\Models\WaitListStatus;
use App\Module\User\Resources\UserShowResource;

/**
 * @OA\Schema(
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="clientId", type="integer", description="Id заказа/накладной"),
 *     @OA\Property(property="clientType", type="string", description="Запись для забора/доставки"),
 *     @OA\Property(property="value", type="string", description="Значение (Дата/Адрес и т.д)"),
 *     @OA\Property(property="comment", type="string", description="Коммент"),
 *     @OA\Property(property="clientTypeId", type="integer", description="ID типа клиента доставка = 1 / забор = 2"),
 *     @OA\Property(property="number", type="integer", description="Номер заказа/накладной"),
 *     @OA\Property(property="source", type="string", description="Источник создания листа ожидания"),
 *     @OA\Property(property="createdAt", type="string", description="Дата создания"),
 *     @OA\Property(property="status", type="object", ref="#/components/schemas/RefStatusResource"),
 *     @OA\Property(property="state", type="object", ref="#/components/schemas/WaitListStatusStateResource"),
 *     @OA\Property(property="user", type="object", ref="#/components/schemas/UserShowResource"),
 * )
 *
 * @property WaitListStatus $resource
 */
final class WaitListStatusResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        return [
            'id'           => $this->resource->id,
            'clientId'     => $this->resource->client_id,
            'value'        => $this->resource->value,
            'comment'      => $this->resource->comment,
            'clientType'   => $this->resource->getClientTitle(),
            'clientTypeId' => $this->resource->getClientTypeId(),
            'number'       => $this->resource->getNumber(),
            'source'       => $this->resource->source,
            'createdAt'    => $this->resource->created_at?->toDateTimeString(),
            'state'        => new WaitListStatusStateResource($this->resource),
            'status'       => new RefStatusResource($this->resource->refStatus),
            'user'         => new UserShowResource($this->resource->user),
        ];
    }
}
