<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Resources\CourierPayment;

use Illuminate\Http\Resources\Json\JsonResource;

final class CourierPaymentsResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'data' => CourierPaymentResource::collection($this->resource)
        ];
    }
}
