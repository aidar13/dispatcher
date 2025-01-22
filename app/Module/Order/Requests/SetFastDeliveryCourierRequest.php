<?php

declare(strict_types=1);

namespace App\Module\Order\Requests;

use App\Module\Order\DTO\SetFastDeliveryCourierDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     @OA\Property(property="courierName", type="string", example="Андрей"),
 *     @OA\Property(property="courierPhone", type="string", example="Андрей"),
 *     @OA\Property(property="trackingUrl", type="string", example="Андрей"),
 * )
 */
final class SetFastDeliveryCourierRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'courierName'    => ['nullable', 'string'],
            'courierPhone'   => ['nullable', 'string'],
            'trackLink'      => ['nullable', 'string'],
            'internalStatus' => ['nullable', 'string'],
            'price'          => ['nullable', 'string']
        ];
    }

    public function getDTO(): SetFastDeliveryCourierDTO
    {
        return SetFastDeliveryCourierDTO::fromRequest($this);
    }
}
