<?php

declare(strict_types=1);

namespace App\Module\User\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *      @OA\Property(property="id", type="integer"),
 *      @OA\Property(property="name", type="string"),
 *      @OA\Property(property="email", type="string"),
 *      @OA\Property(property="phone", type="phone"),
 * )
 *
 * @property User $resource
 */
final class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'    => $this->resource->id,
            'name'  => $this->resource->name,
            'email' => $this->resource->email,
            'phone' => $this->resource->phone,
        ];
    }
}
