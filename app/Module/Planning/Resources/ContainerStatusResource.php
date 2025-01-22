<?php

declare(strict_types=1);

namespace App\Module\Planning\Resources;

use App\Http\Resources\BaseJsonResource;
use App\Module\Planning\Models\ContainerStatus;

/**
 * @OA\Schema(
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", description="Название статуса контейнера", example="Собран"),
 * )
 *
 * @property ContainerStatus $resource
 */
final class ContainerStatusResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        return [
            'id'    => $this->resource->id,
            'title' => $this->resource->title
        ];
    }
}
