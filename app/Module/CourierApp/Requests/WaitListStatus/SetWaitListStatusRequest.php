<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Requests\WaitListStatus;

use App\Module\CourierApp\DTO\WaitListStatus\SetWaitListStatusDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema (
 *     required={"statusCode"},
 *     @OA\Property(property="statusCode", type="integer", description="Код статуса", example="301"),
 *     @OA\Property(property="comment", type="string", description="Комментарий"),
 *     @OA\Property(property="sourceId", type="integer", description="Откуда запрос"),
 * )
 */
final class SetWaitListStatusRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'statusCode' => ['required', 'integer'],
            'comment'    => ['nullable', 'string'],
            'sourceId'   => ['nullable', 'integer'],
        ];
    }

    public function getDTO(): SetWaitListStatusDTO
    {
        return SetWaitListStatusDTO::fromRequest($this);
    }
}
