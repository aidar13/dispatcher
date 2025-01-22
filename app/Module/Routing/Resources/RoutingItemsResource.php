<?php

declare(strict_types=1);

namespace App\Module\Routing\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

final class RoutingItemsResource extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'data' => RoutingItemResource::collection($this->resource)
        ];
    }
}
