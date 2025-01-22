<?php

declare(strict_types=1);

namespace App\Module\Planning\Resources;

use App\Http\Resources\BaseResourceCollection;

final class PlanningCourierResource extends BaseResourceCollection
{
    public function toArray($request): array
    {
        return [
            'data' => PlanningCourierShowResource::collection($this->resource)
        ];
    }
}
