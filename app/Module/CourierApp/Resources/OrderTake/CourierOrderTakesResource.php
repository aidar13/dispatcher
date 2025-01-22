<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Resources\OrderTake;

use Illuminate\Http\Resources\Json\ResourceCollection;

final class CourierOrderTakesResource extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'data' => CourierOrderTakeResource::collection($this->resource)
        ];
    }
}
