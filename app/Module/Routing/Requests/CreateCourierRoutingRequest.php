<?php

declare(strict_types=1);

namespace App\Module\Routing\Requests;

use App\Module\Routing\DTO\CreateCourierRoutingDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema (
 *     required={"courierId"},
 *
 *     @OA\Property(property="courierId", type="integer", description="ID курьера", example="1"),
 * )
 */
final class CreateCourierRoutingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'courierId' => ['required', 'integer'],
        ];
    }

    public function getDTO(): CreateCourierRoutingDTO
    {
        return CreateCourierRoutingDTO::fromRequest($this);
    }
}
