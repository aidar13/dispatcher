<?php

declare(strict_types=1);

namespace App\Module\Status\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

final class StatusTypesResource extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'data' => StatusTypeResource::collection($this->resource)
        ];
    }
}
