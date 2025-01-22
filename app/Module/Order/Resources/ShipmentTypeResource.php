<?php

declare(strict_types=1);

namespace App\Module\Order\Resources;

use App\Http\Resources\BaseJsonResource;
use App\Module\Order\Models\ShipmentType;

/**
 * @OA\Schema(
 *     @OA\Property(property="id", type="integer", description="ID", example="1"),
 *     @OA\Property(property="title", type="string", description="Название", example="Авто"),
 * )
 * @property ShipmentType $resource
 */
final class ShipmentTypeResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        return [
            'id'    => $this->resource?->id,
            'title' => $this->resource?->title
        ];
    }
}
