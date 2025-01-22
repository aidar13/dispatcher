<?php

declare(strict_types=1);

namespace App\Module\Courier\Resources;

use App\Module\Courier\Models\Courier;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="fullName", type="string", example="Test Test"),
 *     @OA\Property(property="phoneNumber", type="string", example="+77777777777"),
 *     @OA\Property(property="iin", type="string", example="220011330022"),
 * )
 *
 * @property Courier $resource
 */
final class CourierShortInfoResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'          => $this->resource->id,
            'fullName'    => $this->resource->full_name,
            'phoneNumber' => $this->resource->phone_number,
            'iin'         => $this->resource->iin,
        ];
    }
}
