<?php

declare(strict_types=1);

namespace App\Module\File\Requests;

use App\Module\File\DTO\UploadFileDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema (
 *     required={"type", "file", "clientId"},
 *     @OA\Property(property="type", type="int", example="1", description="тип файла"),
 *     @OA\Property(property="clientId", type="int", example="1", description="Client Id"),
 *     @OA\Property(property="file", type="binary", example="file jpg,jpeg,bmp,png,gif,svg,pdf"))),
 * )
 */
final class UploadFileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'type'     => ['required', 'int'],
            'clientId' => ['required', 'int'],
            'file'     => ['required', 'mimes:jpg,jpeg,bmp,png,gif,svg,pdf'],
        ];
    }

    public function getDTO(): UploadFileDTO
    {
        return UploadFileDTO::fromRequest($this);
    }
}
