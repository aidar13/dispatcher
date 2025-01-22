<?php

declare(strict_types=1);

namespace App\Module\Order\Resources;

use App\Http\Resources\BaseJsonResource;
use App\Module\Order\Models\AdditionalServiceValue;

/**
 * @OA\Schema(
 *      @OA\Property(property="id",type="integer"),
 *      @OA\Property(property="typeId",type="integer"),
 *      @OA\Property(property="typeName",type="string"),
 *      @OA\Property(property="typeCode",type="string"),
 *      @OA\Property(property="value",type="integer"),
 *      @OA\Property(property="duration",type="integer"),
 *      @OA\Property(property="costPerHour",type="integer"),
 *      @OA\Property(property="paidPricePerHour",type="integer"),
 *      @OA\Property(property="costTotal",type="integer"),
 *      @OA\Property(property="paidPrice_total",type="integer"),
 *      @OA\Property(property="statusId",type="integer"),
 * )
 */
final class AdditionalServiceValuesResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        $data = [];

        if (empty($this->resource)) {
            return [];
        }

        /** @var AdditionalServiceValue $additionalServiceValue */
        foreach ($this->resource->additionalServiceValues as $additionalServiceValue) {
            $item = [
                'id'               => $additionalServiceValue->id,
                'typeId'           => $additionalServiceValue->type_id,
                'typeName'         => $additionalServiceValue->type->name,
                'typeCode'         => $additionalServiceValue->type->code,
                'value'            => $additionalServiceValue->value,
                'duration'         => $additionalServiceValue->duration,
                'costPerHour'      => $additionalServiceValue->cost_per_hour,
                'paidPricePerHour' => $additionalServiceValue->paid_price_per_hour,
                'costTotal'        => $additionalServiceValue->cost_total,
                'paidPriceTotal'   => $additionalServiceValue->paid_price_total,
                'statusId'         => $additionalServiceValue->status_id,
            ];

            $data[] = $item;
        }

        return $data;
    }
}
