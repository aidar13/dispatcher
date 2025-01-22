<?php

declare(strict_types=1);

namespace App\Module\Status\Resources;

use App\Http\Resources\BaseJsonResource;
use App\Module\Status\Models\StatusType;

/**
 * @OA\Schema(
 *     @OA\Property(property="id",type="integer", description="Status Id", example="1"),
 *     @OA\Property(property="title",type="string", description="Наименование статуса", example="Не назначен на курьера"),
 * )
 * @property StatusType $resource
 */
final class StatusTypeResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        return [
            'id'    => $this->resource->id,
            'title' => $this->resource->title,
        ];
    }
}
