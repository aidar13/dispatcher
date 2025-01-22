<?php

declare(strict_types=1);

namespace App\Module\Take\Requests;

use App\Module\Take\DTO\OrderTakeShowDTO;
use Illuminate\Foundation\Http\FormRequest;

final class OrderTakeShowRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'limit'              => ['nullable', 'integer'],
            'page'               => ['nullable', 'integer'],
            'dispatcherSectorId' => ['nullable', 'int', 'exists:dispatcher_sectors,id'],
            'courierId'          => ['nullable', 'int'],
            'companyId'          => ['nullable', 'int'],
            'address'            => ['nullable', 'string'],
            'cityId'             => ['nullable', 'int'],
            'orderNumber'        => ['nullable', 'string'],
            'periodId'           => ['nullable', 'int'],
            'dateFrom'           => ['nullable', 'string', 'date_format:Y-m-d'],
            'dateTo'             => ['nullable', 'string', 'date_format:Y-m-d'],
            'statusIds'          => ['nullable', 'array'],
            'notInStatusIds'     => ['nullable', 'array'],
            'waitListStatusIds'  => ['nullable', 'array'],
            'createdAtFrom'      => ['nullable'],
            'createdAtTo'        => ['nullable'],
            'hasPackType'        => ['nullable', 'boolean'],
            'waitListComment'    => ['nullable', 'string'],
            'incompletedAllTime' => ['nullable', 'boolean'],
        ];
    }

    public function getDTO(): OrderTakeShowDTO
    {
        return OrderTakeShowDTO::fromRequest($this);
    }
}
