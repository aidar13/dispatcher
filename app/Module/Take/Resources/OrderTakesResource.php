<?php

declare(strict_types=1);

namespace App\Module\Take\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

final class OrderTakesResource extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'data' => OrderTakeResource::collection($this->resource)
        ];
    }
}
