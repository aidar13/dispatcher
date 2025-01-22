<?php

declare(strict_types=1);

namespace App\Module\Courier\Requests;

use App\Module\Courier\DTO\CreateCourierScheduleDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     required={"courierId"},
 *     @OA\Property(property="courierId", type="integer", example=1),
 *     @OA\Property(
 *         property="schedules",
 *         type="array",
 *         description="Режимы работы",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="weekday", type="integer", example=0),
 *             @OA\Property(property="workTimeFrom", type="string", example="12:00:00"),
 *             @OA\Property(property="workTimeUntil", type="string", example="18:00:00")
 *         )
 *     )
 * )
 */
final class CreateCourierScheduleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'courierId'                  => ['required'],
            'schedules'                  => ['nullable', 'array'],
            'schedules.*.weekday'        => ['integer', 'between:0,6'],
            'schedules.*.workTimeFrom'   => ['string'],
            'schedules.*.workTimeUntil'  => ['string'],
        ];
    }

    public function getDTO(): CreateCourierScheduleDTO
    {
        return CreateCourierScheduleDTO::fromRequest($this);
    }
}
