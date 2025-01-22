<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Requests\CourierLocation;

use App\Module\CourierApp\DTO\CourierLocation\CreateCourierLocationDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema (
 *     @OA\Property(property="latitude", type="string", example="43.234", description="широта"),
 *     @OA\Property(property="longitude", type="string", example="74.123", description="долгота"),
 *     @OA\Property(property="time", type="string", example="2024-11-11 11:11", description="время когда был отправлен, при случае если интернет был недоступен и отправляется потом в формате Y-m-d H:s"),
 * )
 */
final class CreateCourierLocationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'latitude'  => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'time'      => ['nullable', 'string'],
        ];
    }

    public function getDTO(): CreateCourierLocationDTO
    {
        return CreateCourierLocationDTO::fromRequest($this);
    }
}
