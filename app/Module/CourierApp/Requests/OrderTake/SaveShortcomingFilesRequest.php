<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Requests\OrderTake;

use App\Module\CourierApp\DTO\OrderTake\SaveShortcomingFilesDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     required={"orderId", "productFiles", "shortcomingFiles"},
 *     @OA\Property(property="orderId", type="int", description="ID заказа"),
 *     @OA\Property(property="productFiles", type="array", description="Фото товара", @OA\Items(
 *         @OA\Property(property="file", type="binary", example="file png,jpeg,jpg")
 *     )),
 *     @OA\Property(property="shortcomingFiles", type="array", description="Акт об обноружении недостатков", @OA\Items(
 *          @OA\Property(property="file", type="binary", example="file png,jpeg,jpg")
 *      )),
 * )
 */
final class SaveShortcomingFilesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'orderId'            => ['required', 'integer'],
            'productFiles'       => ['required', 'array'],
            'productFiles.*'     => ['mimes:jpg,jpeg,bmp,png,gif,svg,pdf'],
            'shortcomingFiles'   => ['required', 'array'],
            'shortcomingFiles.*' => ['mimes:jpg,jpeg,bmp,png,gif,svg,pdf'],
        ];
    }

    public function getDTO(): SaveShortcomingFilesDTO
    {
        return SaveShortcomingFilesDTO::fromRequest($this);
    }
}
