<?php

declare(strict_types=1);

namespace App\Module\Delivery\Requests;

use App\Module\Delivery\DTO\RouteSheetIndexDTO;
use Illuminate\Foundation\Http\FormRequest;

final class RouteSheetIndexRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'limit'              => ['nullable', 'integer'],
            'page'               => ['nullable', 'integer'],
            'fromDate'           => ['nullable', 'string', 'date_format:Y-m-d'],
            'toDate'             => ['nullable', 'string', 'date_format:Y-m-d'],
            'courierId'          => ['nullable', 'integer', 'exists:couriers,id'],
            'dispatcherSectorId' => ['nullable', 'exists:route_sheets,dispatcher_sector_id'],
            'cityId'             => ['nullable', 'integer'],
            'waveId'             => ['nullable', 'integer'],
            'sectorId'           => ['nullable', 'integer'],
            'invoiceNumber'      => ['nullable', 'string'],
            'number'             => ['nullable', 'string'],
        ];
    }

    public function getDTO(): RouteSheetIndexDTO
    {
        return RouteSheetIndexDTO::fromRequest($this);
    }
}
