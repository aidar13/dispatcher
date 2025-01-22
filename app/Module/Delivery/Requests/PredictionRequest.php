<?php

declare(strict_types=1);

namespace App\Module\Delivery\Requests;

use App\Module\Delivery\DTO\PredictionDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     required={"dispatcherSectorId"},
 *
 *     @OA\Property(property="dispatcherSectorId", type="integer", example=1),
 *     @OA\Property(property="date", type="string", example="2023-08-11"),
 * )
 */
final class PredictionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'dispatcherSectorId' => ['required', 'integer'],
            'date'               => ['nullable', 'date_format:Y-m-d'],
        ];
    }

    public function getDTO(): PredictionDTO
    {
        return PredictionDTO::fromRequest($this);
    }
}
