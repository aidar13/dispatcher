<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Requests\Delivery;

use App\Module\CourierApp\DTO\Delivery\ApproveDeliveryDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     required={"deliveryReceiverName", "deliveredAt", "attachments"},
 *
 *     @OA\Property(property="deliveryReceiverName", type="integer", example="Иван Иванов"),
 *     @OA\Property(property="attachments", type="array", @OA\Items(@OA\Property(property="file", type="binary", example="file png,jpeg,jpg"))),
 *     @OA\Property(property="deliveredAt", type="integer", example="2022-01-01T10:00:00"),
 * )
 */
final class ApproveDeliveryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'deliveryReceiverName' => ['required', 'string'],
            'deliveredAt'          => ['required', 'date'],
            'attachments'          => ['required', 'array'],
            'attachments.*'        => ['mimes:jpg,jpeg,bmp,png,gif,svg,pdf'],
        ];
    }

    public function getDTO(): ApproveDeliveryDTO
    {
        return ApproveDeliveryDTO::fromRequest($this);
    }
}
