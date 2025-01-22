<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Requests\Delivery;

use App\Module\CourierApp\DTO\Delivery\CourierDeliveryShowDTO;
use Illuminate\Foundation\Http\FormRequest;

final class CourierDeliveryShowRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'limit'            => ['nullable', 'integer'],
            'page'             => ['nullable', 'integer'],
            'longitude'        => ['nullable', 'string'],
            'latitude'         => ['nullable', 'string'],
            'search'           => ['nullable', 'string'],
            'statusIds'        => ['nullable', 'array'],
            'notInStatusIds'   => ['nullable', 'array'],
            'createdAtFrom'    => ['nullable', 'string'],
            'createdAtTo'      => ['nullable', 'string'],
            'deliveredAtFrom'  => ['nullable', 'string'],
            'deliveredAtTo'    => ['nullable', 'string'],
            'deliveryDateFrom' => ['nullable', 'string'],
            'deliveryDateTo'   => ['nullable', 'string'],
        ];
    }

    public function getDTO(): CourierDeliveryShowDTO
    {
        return CourierDeliveryShowDTO::fromRequest($this);
    }
}
