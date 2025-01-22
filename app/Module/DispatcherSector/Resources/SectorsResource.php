<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

final class SectorsResource extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'data' => SectorShowResource::collection($this->resource)
        ];
    }
}
