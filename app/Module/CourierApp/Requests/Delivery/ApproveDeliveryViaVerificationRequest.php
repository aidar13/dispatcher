<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Requests\Delivery;

use App\Module\CourierApp\DTO\Delivery\ApproveDeliveryViaVerificationDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     required={"invoiceNumber", "verifyType", "deliveredAt"},
 *
 *     @OA\Property(property="invoiceNumber", type="string", example="Иван Иванов"),
 *     @OA\Property(property="verifyType", type="int"),
 *     @OA\Property(property="deliveredAt", type="string", example="2022-01-01T10:00:00"),
 * )
 */
final class ApproveDeliveryViaVerificationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'invoiceNumber' => ['required'],
            'verifyType'    => ['required'],
            'deliveredAt'   => ['required'],
        ];
    }

    public function getDTO(): ApproveDeliveryViaVerificationDTO
    {
        return ApproveDeliveryViaVerificationDTO::fromRequest($this);
    }
}
