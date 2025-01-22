<?php

declare(strict_types=1);

namespace App\Module\Delivery\Resources;

use App\Http\Resources\BaseResourceCollection;

final class RouteSheetsResource extends BaseResourceCollection
{
    public function toArray($request): array
    {
        return [
            'data' => RouteSheetResource::collection($this->resource)
        ];
    }
}
