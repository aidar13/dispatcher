<?php

declare(strict_types=1);

namespace App\Module\Take\Resources;

use App\Helpers\StringHelper;
use App\Http\Resources\BaseJsonResource;
use App\Module\DispatcherSector\Resources\SectorResource;
use App\Module\Take\Models\Customer;

/**
 * @OA\Schema(
 *     @OA\Property(property="id",type="integer", description="Customer Id", example="10"),
 *     @OA\Property(property="fullName",type="string", description="Полное имя", example="Full Name"),
 *     @OA\Property(property="address",type="string", description="Адрес", example="Алматы 101 толе би"),
 *     @OA\Property(property="phone",type="string", description="номер телефона", example="7777123456789"),
 *     @OA\Property(property="additionalPhone",type="string", description="доп номер телефона", example="7777123456789"),
 *     @OA\Property(
 *         property="sector",
 *         ref="#/components/schemas/SectorShowResource"
 *     ),
 * )
 * @property Customer $resource
 */
final class CustomerResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        return [
            'id'              => $this->resource->id,
            'fullName'        => $this->resource->full_name,
            'address'         => $this->resource->address,
            'shortAddress'    => StringHelper::removeWords($this->resource->address),
            'phone'           => $this->resource->phone,
            'additionalPhone' => $this->resource->additional_phone,
            'sector'          => new SectorResource($this->resource->sector, false),
        ];
    }
}
