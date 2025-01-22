<?php

declare(strict_types=1);

namespace App\Module\Courier\Requests;

use App\Module\Courier\DTO\UpdateCourierDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     required={"iin", "fullName", "phoneNumber", "paymentRateType", "paymentAmount", "dispatcherSectorId", "companyId", "scheduleTypeId", "carId"},
 *
 *     @OA\Property(property="iin", type="string", example="123123456456", description="ИИН курьера"),
 *     @OA\Property(property="fullName", type="string", example="Test Test", description="ФИО"),
 *     @OA\Property(property="phoneNumber", type="string", example="+77777777777", description="номер телефона"),
 *     @OA\Property(property="paymentRateType", type="integer", example=1, description="Тип оплаты"),
 *     @OA\Property(property="paymentAmount", type="string", example="500.25", description="Сумма оплаты"),
 *     @OA\Property(property="dispatcherSectorId", type="integer", example=1, description="Сектор биспетчера"),
 *     @OA\Property(property="companyId", type="integer", example=1, description="Айди компаний"),
 *     @OA\Property(property="scheduleTypeId", type="integer", example=1, description="ID волны(смены)"),
 *     @OA\Property(property="carId", type="integer", example=1, description="ID машины"),
 * )
 */
final class UpdateCourierRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'iin'                => ['required', 'string'],
            'fullName'           => ['required', 'string'],
            'dispatcherSectorId' => ['required', 'exists:dispatcher_sectors,id'],
            'phoneNumber'        => ['required', 'phone:KZ'],
            'paymentRateType'    => ['required', 'integer'],
            'paymentAmount'      => ['nullable', 'numeric'],
            'companyId'          => ['required', 'integer'],
            'scheduleTypeId'     => ['required', 'exists:courier_schedule_types,id'],
            'carId'              => ['required', 'exists:cars,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'scheduleTypeId.exists' => 'Выбран неверный Волна.',
        ];
    }

    public function getDTO(): UpdateCourierDTO
    {
        return UpdateCourierDTO::fromRequest($this);
    }
}
