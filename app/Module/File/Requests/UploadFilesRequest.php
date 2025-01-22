<?php

declare(strict_types=1);

namespace App\Module\File\Requests;

use App\Module\File\DTO\UploadFilesDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema (
 *     required={"type", "file"},
 *     @OA\Property(property="type", type="int", example="1", description="тип файла"),
 *     @OA\Property(property="files", type="array", @OA\Items(@OA\Property(type="binary", example="file jpg,jpeg,bmp,png,gif,svg,pdf")))),
 * )
 */
final class UploadFilesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'type'    => ['required', 'int'],
            'files'   => ['array', 'required'],
            'files.*' => ['mimes:jpg,jpeg,bmp,png,gif,svg,pdf'],
        ];
    }

    public function getDTO(): UploadFilesDTO
    {
        return UploadFilesDTO::fromRequest($this);
    }
}
