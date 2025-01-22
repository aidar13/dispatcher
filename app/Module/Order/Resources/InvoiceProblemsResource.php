<?php

declare(strict_types=1);

namespace App\Module\Order\Resources;

use App\Module\Order\Models\Invoice;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="invoiceNumber", type="string", example="SP0000001"),
 *     @OA\Property(property="slaDate", type="string", example="2024-01-17"),
 *     @OA\Property(
 *         property="problems",
 *         type="array",
 *         @OA\Items(type="string", example="Опаздывает")
 *     ),
 * )
 * @property Invoice $resource
 */
final class InvoiceProblemsResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'            => $this->resource->id,
            'invoiceNumber' => $this->resource->invoice_number,
            'slaDate'       => $this->resource->sla_date,
            'problems'      => $this->resource->problems->toArray(),
        ];
    }
}
