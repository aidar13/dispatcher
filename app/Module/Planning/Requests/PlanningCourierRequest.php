<?php

declare(strict_types=1);

namespace App\Module\Planning\Requests;

use App\Module\Planning\DTO\PlanningCourierShowDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     required={"dispatcherSectorId", "waveId", "date"},
 *
 *     @OA\Property(property="dispatcherSectorId", type="integer", example=1),
 *     @OA\Property(property="waveId", type="integer", example=1),
 *     @OA\Property(property="date", type="string", example="2023-08-11")
 * )
 */
final class PlanningCourierRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'dispatcherSectorId' => ['required', 'integer'],
            'waveId'             => ['required', 'integer'],
            'date'               => ['required', 'date_format:Y-m-d']
        ];
    }

    public function getDTO(): PlanningCourierShowDTO
    {
        return PlanningCourierShowDTO::fromRequest($this);
    }
}
