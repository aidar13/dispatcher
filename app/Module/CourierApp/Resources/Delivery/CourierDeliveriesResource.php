<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Resources\Delivery;

use Illuminate\Http\Resources\Json\ResourceCollection;

final class CourierDeliveriesResource extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'data' => CourierDeliveryResource::collection($this->resource)
        ];
    }
}
