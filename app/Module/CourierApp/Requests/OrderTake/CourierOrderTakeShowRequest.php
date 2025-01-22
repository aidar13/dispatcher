<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Requests\OrderTake;

use App\Module\CourierApp\DTO\OrderTake\CourierOrderTakeShowDTO;
use Illuminate\Foundation\Http\FormRequest;

class CourierOrderTakeShowRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'limit'          => ['nullable', 'integer'],
            'page'           => ['nullable', 'integer'],
            'longitude'      => ['nullable', 'string'],
            'latitude'       => ['nullable', 'string'],
            'search'         => ['nullable', 'string'],
            'statusIds'      => ['nullable', 'array'],
            'notInStatusIds' => ['nullable', 'array'],
            'dateFrom'       => ['nullable', 'string'],
            'dateTo'         => ['nullable', 'string'],
            'takenAtFrom'    => ['nullable', 'string'],
            'takenAtTo'      => ['nullable', 'string'],
        ];
    }

    public function getDTO(): CourierOrderTakeShowDTO
    {
        return CourierOrderTakeShowDTO::fromRequest($this);
    }
}
