<?php

declare(strict_types=1);

namespace App\Module\Courier\Resources;

use App\Module\Courier\Models\CourierStatus;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="string", type="title", example="Активный"),
 * )
 *
 * @property CourierStatus $resource
 */
final class CourierStatusResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'    => $this->resource->id,
            'title' => $this->resource->title,
        ];
    }
}
