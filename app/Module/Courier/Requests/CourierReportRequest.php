<?php

declare(strict_types=1);

namespace App\Module\Courier\Requests;

use App\Module\Courier\DTO\CourierReportDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     @OA\Property(property="limit",type="integer", example=20),
 *     @OA\Property(property="page",type="integer", example=1),
 *     @OA\Property(property="fromDate",type="string"),
 *     @OA\Property(property="toDate",type="string"),
 *     @OA\Property(property="courierId",type="integer", example=1),
 *     @OA\Property(property="dispatcherSectorId",type="integer", example=1, description="Id диспетчер сектора"),
 *     @OA\Property(property="hasReturn",type="integer", example=1, description="Есть ли возврат выдачи (1=Нет,2=Да, null=все)"),
 *     @OA\Property(property="hasCash",type="integer", example=1, description="Есть ли наличка (1=Нет,2=Да, null=все)"),
 *     @OA\Property(property="hasCodPayment",type="integer", example=1, description="Есть ли налож платеж (1=Нет,2=Да, null=все)"),
 * )
 */
final class CourierReportRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'limit'              => ['nullable', 'integer'],
            'page'               => ['nullable', 'integer'],
            'fromDate'           => ['nullable', 'string', 'date_format:Y-m-d'],
            'toDate'             => ['nullable', 'string', 'date_format:Y-m-d'],
            'courierId'          => ['nullable', 'integer', 'exists:couriers,id'],
            'dispatcherSectorId' => ['nullable', 'integer', 'exists:dispatcher_sectors,id'],
            'hasReturn'          => ['nullable', 'integer'],
            'hasCash'            => ['nullable', 'integer'],
            'hasCodPayment'      => ['nullable', 'integer'],
        ];
    }

    public function getDTO(): CourierReportDTO
    {
        return CourierReportDTO::fromRequest($this);
    }
}
