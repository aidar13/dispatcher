<?php

declare(strict_types=1);

namespace App\Module\Monitoring\Requests;

use App\Module\Monitoring\DTO\TakeInfoShowDTO;
use Illuminate\Foundation\Http\FormRequest;

final class TakeInfoShowRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'dispatcherSectorId' => ['nullable', 'numeric'],
            'createdAtFrom'      => ['nullable', 'string'],
            'createdAtTo'        => ['nullable', 'string'],
            'takeDateFrom'       => ['nullable', 'string'],
            'takeDateTo'         => ['nullable', 'string'],
        ];
    }

    public function getDTO(): TakeInfoShowDTO
    {
        return TakeInfoShowDTO::fromRequest($this);
    }
}
