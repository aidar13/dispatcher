<?php

declare(strict_types=1);

namespace App\Module\Company\Resources;

use App\Http\Resources\BaseJsonResource;
use App\Module\Company\Models\Company;

/**
 * @OA\Schema(
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="shortName", type="string"),
 *     @OA\Property(property="bin", type="string")
 * )
 * @property Company $resource
 */
final class CompanyResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        return [
            'id'        => $this->resource->id,
            'name'      => $this->resource->name,
            'shortName' => $this->resource->short_name,
            'bin'       => $this->resource->bin,
        ];
    }
}
