<?php

declare(strict_types=1);

namespace App\Module\Planning\Resources;

use App\Http\Resources\BaseResourceCollection;

final class PlanningResource extends BaseResourceCollection
{
    public function toArray($request): array
    {
        return [
            'data' => PlanningShowResource::collection($this->resource)
        ];
    }
}
