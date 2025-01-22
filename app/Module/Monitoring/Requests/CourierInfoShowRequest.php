<?php

declare(strict_types=1);

namespace App\Module\Monitoring\Requests;

use App\Module\Monitoring\DTO\CourierInfoShowDTO;
use Illuminate\Foundation\Http\FormRequest;

final class CourierInfoShowRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'dispatcherSectorId' => ['nullable', 'numeric'],
            'createdAtFrom'      => ['nullable', 'string'],
            'createdAtTo'        => ['nullable', 'string'],
        ];
    }

    public function getDTO(): CourierInfoShowDTO
    {
        return CourierInfoShowDTO::fromRequest($this);
    }
}
