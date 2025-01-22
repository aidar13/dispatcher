<?php

declare(strict_types=1);

namespace App\Module\Courier\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

final class CourierSchedulesResource extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'data' => CourierScheduleResource::collection($this->resource)
        ];
    }
}
