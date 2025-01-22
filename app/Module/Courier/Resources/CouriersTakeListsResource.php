<?php

declare(strict_types=1);

namespace App\Module\Courier\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

final class CouriersTakeListsResource extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'data' => CourierTakeListResource::collection($this->resource)
        ];
    }
}
