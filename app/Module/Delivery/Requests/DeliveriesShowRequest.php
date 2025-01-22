<?php

declare(strict_types=1);

namespace App\Module\Delivery\Requests;

use App\Module\Delivery\DTO\DeliveryShowDTO;
use Illuminate\Foundation\Http\FormRequest;

final class DeliveriesShowRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'limit'              => ['nullable', 'integer'],
            'page'               => ['nullable', 'integer'],
            'dispatcherSectorId' => ['nullable', 'exists:dispatcher_sectors,id'],
            'containerId'        => ['nullable', 'exists:containers,id'],
            'statusIds'          => ['nullable', 'array'],
            'notInStatusIds'     => ['nullable', 'array'],
            'waitListStatusIds'  => ['nullable', 'array'],
            'invoiceNumber'      => ['nullable'],
            'address'            => ['nullable'],
            'companyId'          => ['nullable'],
            'courierId'          => ['nullable'],
            'sectorId'           => ['nullable'],
            'createdAtFrom'      => ['nullable'],
            'createdAtTo'        => ['nullable'],
            'waitListComment'    => ['nullable', 'string'],
        ];
    }

    public function getDTO(): DeliveryShowDTO
    {
        return DeliveryShowDTO::fromRequest($this);
    }
}
