<?php

declare(strict_types=1);

namespace App\Module\Monitoring\Resources;

use App\Http\Resources\BaseJsonResource;

final class CourierInfosResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        return [
            'data' => CourierMonitoringInfoResource::collection($this->resource)
        ];
    }
}
