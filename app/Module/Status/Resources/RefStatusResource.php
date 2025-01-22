<?php

declare(strict_types=1);

namespace App\Module\Status\Resources;

use App\Http\Resources\BaseJsonResource;
use App\Module\Status\Models\RefStatus;

/**
 * @OA\Schema(
 *     @OA\Property(property="id",type="integer", description="Status Id", example="1"),
 *     @OA\Property(property="code",type="integer", description="Code", example="201"),
 *     @OA\Property(property="name",type="string", description="Наименование статуса", example="Переадресация"),
 * )
 * @property RefStatus $resource
 */
final class RefStatusResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        return [
            'id'   => $this->resource->id,
            'name' => $this->resource->name,
            'code' => $this->resource->code,
        ];
    }
}
