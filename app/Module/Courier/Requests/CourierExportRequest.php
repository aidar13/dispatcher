<?php

declare(strict_types=1);

namespace App\Module\Courier\Requests;

use App\Module\Courier\DTO\CourierExportDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     @OA\Property(property="fromDate",type="string"),
 *     @OA\Property(property="toDate",type="string"),
 *     @OA\Property(property="statusIds", @OA\Schema(type="array", @OA\Items(type="integer", example=1))),
 *     @OA\Property(property="dispatcherSectorIds", @OA\Schema(type="array", @OA\Items(type="integer", example=1))),
 *     @OA\Property(property="name",type="string"),
 *     @OA\Property(property="iin",type="string"),
 *     @OA\Property(property="phoneNumber",type="string"),
 *     @OA\Property(property="companyId",type="numberic"),
 *     @OA\Property(property="carNumber",type="string"),
 *     @OA\Property(property="carModel",type="string"),
 *     @OA\Property(property="shiftId",type="integer"),
 * )
 */
final class CourierExportRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'fromDate'            => ['nullable', 'string'],
            'toDate'              => ['nullable', 'string'],
            'statusIds'           => ['nullable', 'array'],
            'dispatcherSectorIds' => ['nullable', 'array'],
            'createdAtFrom'       => ['nullable', 'string'],
            'createdAtUntil'      => ['nullable', 'string'],
            'name'                => ['nullable', 'string'],
            'iin'                 => ['nullable', 'string'],
            'phoneNumber'         => ['nullable', 'string'],
            'companyId'           => ['nullable', 'numeric'],
            'carNumber'           => ['nullable', 'string'],
            'carModel'            => ['nullable', 'string'],
            'shiftId'             => ['nullable', 'integer'],
            'id'                  => ['nullable', 'integer'],
            'code1C'              => ['nullable', 'string'],
        ];
    }

    public function getDTO(): CourierExportDTO
    {
        return CourierExportDTO::fromRequest($this);
    }
}
