<?php

declare(strict_types=1);

namespace App\Module\Take\Requests;

use App\Module\Take\DTO\OrderPeriodDTO;
use Illuminate\Foundation\Http\FormRequest;

final class OrderPeriodRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'limit'              => ['nullable', 'integer'],
            'page'               => ['nullable', 'integer'],
        ];
    }

    public function getDTO(): OrderPeriodDTO
    {
        return OrderPeriodDTO::fromRequest($this);
    }
}
