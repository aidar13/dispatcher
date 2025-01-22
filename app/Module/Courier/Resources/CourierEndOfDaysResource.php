<?php

declare(strict_types=1);

namespace App\Module\Courier\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

final class CourierEndOfDaysResource extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'data' => CourierEndOfDayResource::collection($this->resource)
        ];
    }
}
