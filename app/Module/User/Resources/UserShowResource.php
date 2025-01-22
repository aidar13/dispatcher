<?php

declare(strict_types=1);

namespace App\Module\User\Resources;

use App\Http\Resources\BaseJsonResource;
use App\Models\User;

/**
 * @OA\Schema(
 *      @OA\Property(property="id", type="integer"),
 *      @OA\Property(property="name", type="string"),
 *      @OA\Property(property="email", type="string"),
 * )
 *
 * @property User $resource
 */
final class UserShowResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        return [
            'id'    => $this->resource->id,
            'name'  => $this->resource->name,
            'email' => $this->resource->email,
        ];
    }
}
