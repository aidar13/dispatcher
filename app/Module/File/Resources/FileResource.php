<?php

declare(strict_types=1);

namespace App\Module\File\Resources;

use App\Http\Resources\BaseJsonResource;
use App\Module\File\Models\File;

/**
 * @OA\Schema(
 *     @OA\Property(property="id",type="int",description="Id"),
 *     @OA\Property(property="originalName",type="string",description="Название файла"),
 *     @OA\Property(property="userId", type="integer", description="ID пользователя"),
 *     @OA\Property(property="userEmail", type="string", description="Почта пользователя"),
 *     @OA\Property(property="url",type="string",description="URL ссылка"),
 *     @OA\Property(property="fileId",type="int",description="id файла"),
 * )
 * @property File $resource
 */
final class FileResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        return [
            'id'           => $this->resource->id,
            'originalName' => $this->resource->original_name,
            'userId'       => $this->resource->user_id,
            'userEmail'    => $this->resource->user?->email,
            'url'          => $this->resource->getUrl(),
            'uuidHash'     => $this->resource->uuid_hash,
        ];
    }
}
