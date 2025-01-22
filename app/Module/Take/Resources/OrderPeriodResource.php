<?php

declare(strict_types=1);

namespace App\Module\Take\Resources;

use App\Http\Resources\BaseJsonResource;
use App\Module\Take\Models\OrderPeriod;

/**
 * @OA\Schema(
 *     @OA\Property(property="id",type="integer",example="1"),
 *     @OA\Property(property="from",type="string",example="12:00"),
 *     @OA\Property(property="to",type="string",example="18:00"),
 *     @OA\Property(property="title",type="string",example="После обеда (12:00 - 18:00)"),
 * )
 * @property OrderPeriod $resource
 */
final class OrderPeriodResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        return [
            'id'    => $this->resource->id,
            'from'  => $this->resource->from,
            'to'    => $this->resource->to,
            'title' => $this->resource->title,
        ];
    }
}
