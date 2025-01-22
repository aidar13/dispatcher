<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Resources\ShortComingFiles;

use App\Module\File\Resources\FileResource;
use App\Module\Order\Models\Order;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     @OA\Property(property="reportFiles", ref="#/components/schemas/FileResource"),
 *     @OA\Property(property="productFiles", ref="#/components/schemas/FileResource"),
 * )
 *
 * @property Order $resource
 */
final class ShortComingFilesResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'reportFiles'  => FileResource::collection($this->resource->shortcomingReportFiles()),
            'productFiles' => FileResource::collection($this->resource->shortcomingProductFiles()),
        ];
    }
}
