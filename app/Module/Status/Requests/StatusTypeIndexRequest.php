<?php

declare(strict_types=1);

namespace App\Module\Status\Requests;

use App\Module\Status\DTO\StatusTypeIndexDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     @OA\Property(property="limit",type="integer", example=20),
 *     @OA\Property(property="page",type="integer", example=1),
 *     @OA\Property(property="typeId",type="int", example=1, description="Тип статус Айди")
 * )
 */
final class StatusTypeIndexRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'limit'  => ['nullable', 'integer'],
            'page'   => ['nullable', 'integer'],
            'typeId' => ['nullable', 'int', 'exists:status_types,type'],
        ];
    }

    public function messages(): array
    {
        return [
            'typeId.exists' => 'Выбран неверный тип.',
        ];
    }

    public function getDTO(): StatusTypeIndexDTO
    {
        return StatusTypeIndexDTO::fromRequest($this);
    }
}
