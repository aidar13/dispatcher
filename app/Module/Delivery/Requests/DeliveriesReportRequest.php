<?php

declare(strict_types=1);

namespace App\Module\Delivery\Requests;

use App\Module\Delivery\DTO\DeliveryReportDTO;
use App\Module\Delivery\DTO\DeliveryShowDTO;
use Illuminate\Foundation\Http\FormRequest;

final class DeliveriesReportRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'dispatcherSectorId' => ['nullable', 'exists:dispatcher_sectors,id'],
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

    public function getDTO(): DeliveryReportDTO
    {
        return DeliveryReportDTO::fromRequest($this);
    }
}
