<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Requests\CourierPayment;

use App\Module\CourierApp\DTO\CourierPayment\SaveDeliveryCourierPaymentFilesDTO;
use App\Module\CourierApp\DTO\CourierPayment\SaveOrderTakeCourierPaymentFilesDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     required={"clientId", "type", "cost", "checks"},
 *     @OA\Property(property="clientId", type="integer", description="ID клиента"),
 *     @OA\Property(property="type", type="integer", description="Тип расхода (1-За дорогу|2-За парковку)"),
 *     @OA\Property(property="cost", type="integer", description="Сумма расходов"),
 *     @OA\Property(property="checks", type="array", description="Фото чеков за дорогу|парковку", @OA\Items(
 *         type="string",
 *         format="binary",
 *         description="Файл с расширением jpg, jpeg, bmp, png, gif, svg или pdf"
 *     )),
 * )
 */
final class SaveCourierPaymentFilesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'clientId' => ['required', 'integer'],
            'type'     => ['required', 'integer'],
            'cost'     => ['required', 'integer'],
            'checks'   => ['required', 'array'],
            'checks.*' => ['mimes:jpg,jpeg,bmp,png,gif,svg,pdf'],
        ];
    }

    public function getOrderTakeDTO(): SaveOrderTakeCourierPaymentFilesDTO
    {
        return SaveOrderTakeCourierPaymentFilesDTO::fromRequest($this);
    }

    public function getDeliveryDTO(): SaveDeliveryCourierPaymentFilesDTO
    {
        return SaveDeliveryCourierPaymentFilesDTO::fromRequest($this);
    }
}
