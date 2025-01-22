<?php

declare(strict_types=1);

namespace App\Module\Status\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

final class CommentTemplatesResource extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'data' => CommentTemplateResource::collection($this->resource)
        ];
    }
}
