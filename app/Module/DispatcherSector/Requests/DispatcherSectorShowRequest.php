<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Requests;

use App\Module\DispatcherSector\DTO\DispatcherSectorShowDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     @OA\Property(property="limit",type="integer", example=20),
 *     @OA\Property(property="page",type="integer", example=1),
 *     @OA\Property(property="name",type="string", example="Алматы"),
 * )
 */

final class DispatcherSectorShowRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'limit' => ['nullable', 'integer'],
            'page'  => ['nullable', 'integer'],
            'name'  => ['nullable', 'string'],
        ];
    }

    public function getDTO(): DispatcherSectorShowDTO
    {
        return DispatcherSectorShowDTO::fromRequest($this);
    }
}
