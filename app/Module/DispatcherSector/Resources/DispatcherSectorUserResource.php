<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Resources;

use App\Http\Resources\BaseJsonResource;
use App\Module\DispatcherSector\Models\DispatchersSectorUser;

/**
 * @OA\Schema(
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="dispatcherSectorId", type="integer"),
 *     @OA\Property(property="userId", type="integer"),
 * )
 *
 * @property DispatchersSectorUser $resource
 */
final class DispatcherSectorUserResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                 => $this->resource->id,
            'dispatcherSectorId' => $this->resource->dispatcher_sector_id,
            'userId'             => $this->resource->user_id
        ];
    }
}
