<?php

declare(strict_types=1);

namespace App\Module\Car\Resources;

use App\Helpers\DateHelper;
use App\Http\Resources\BaseJsonResource;
use App\Module\Car\Models\Car;
use App\Module\Company\Resources\CompanyResource;

/**
 * @OA\Schema(
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="companyId", type="integer"),
 *     @OA\Property(property="number", type="string"),
 *     @OA\Property(property="model", type="string"),
 *     @OA\Property(property="createdAt", type="string"),
 *     @OA\Property(property="carType", type="object", ref="#/components/schemas/CarTypeResource"),
 *     @OA\Property(property="company", type="object", ref="#/components/schemas/CompanyResource")
 * )
 * @property Car $resource
 */
final class CarResource extends BaseJsonResource
{
    private bool $isExtended;

    public function __construct($resource, bool $isExtended = true)
    {
        parent::__construct($resource);
        $this->isExtended = $isExtended;
    }

    public function toArray($request): array
    {
        $data = [
            'id'        => $this->resource->id,
            'number'    => $this->resource->number,
            'model'     => $this->resource->model,
            'companyId' => $this->resource->company_id,
            'createdAt' => DateHelper::getDateWithTime($this->resource->created_at),
        ];

        if ($this->isExtended) {
            $data = array_merge($data, [
                'carType' => new CarTypeResource($this->resource->carType),
                'company' => new CompanyResource($this->resource->company),
            ]);
        }

        return $data;
    }
}
