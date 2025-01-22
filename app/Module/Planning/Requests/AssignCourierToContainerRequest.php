<?php

declare(strict_types=1);

namespace App\Module\Planning\Requests;

use App\Module\Planning\DTO\AssignCourierToContainerDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 *     required={"invoiceIds"},
 *     @OA\Property(property="courierId", type="integer", example=1),
 *     @OA\Property(property="containerIds", type="array", @OA\Items(example=1, description="ID контейнера")),
 *     @OA\Property(property="isFastDelivery", type="boolean", example=true),
 * )
 */
final class AssignCourierToContainerRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'courierId'      => [Rule::requiredIf(!$this->request->get('provider_id')), 'nullable', 'integer'],
            'containerIds'   => ['required', 'array'],
            'containerIds.*' => ['required', 'integer'],
            'isFastDelivery' => ['nullable', 'bool'],
            'provider_id'    => ['nullable', 'integer']
        ];
    }

    public function getDTO(): AssignCourierToContainerDTO
    {
        return AssignCourierToContainerDTO::fromRequest($this);
    }
}
