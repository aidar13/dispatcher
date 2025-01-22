<?php

declare(strict_types=1);

namespace App\Module\Courier\Resources;

use App\Http\Resources\BaseJsonResource;
use App\Module\Courier\DTO\CourierInvoiceDTO;

/**
 * @OA\Schema(
 *      @OA\Property(property="errors", type="array", description="массив из ошибок", @OA\Items(type="string", example="Ошибка накладной")))
 * )
 *
 * @property CourierInvoiceDTO $resource
 */
final class CloseCourierDayResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        return [
            'errors' => $this->resource->errors,
        ];
    }
}
