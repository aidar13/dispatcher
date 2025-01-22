<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Resources;

use App\Module\DispatcherSector\Models\DispatchersSectorUser;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="email", type="string"),
 *     @OA\Property(property="phone", type="phone"),
 * )
 *
 * @property DispatchersSectorUser $resource
 */
final class DispatcherSectorUserItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'     => $this->resource->user_id,
            'name'   => $this->resource->user?->name ?? '-',
            'email'  => $this->resource->user?->email ?? '-',
            'phone'  => $this->resource->user?->phone ?? '-',
        ];
    }
}
