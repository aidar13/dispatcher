<?php

declare(strict_types=1);

namespace App\Module\Planning\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

final class ContainersResource extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'data' => ContainerItemResource::collection($this->resource)
        ];
    }
}
