<?php

declare(strict_types=1);

namespace App\Module\Courier\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

final class CourierScheduleTypesResource extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'data' => CourierScheduleTypeResource::collection($this->resource)
        ];
    }
}
