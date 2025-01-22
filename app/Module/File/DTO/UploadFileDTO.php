<?php

namespace App\Module\File\DTO;

use App\Module\File\Requests\UploadFileRequest;
use Illuminate\Http\UploadedFile;

final class UploadFileDTO
{
    public int $type;
    public int $clientId;
    public UploadedFile $file;

    public static function fromRequest(UploadFileRequest $request): UploadFileDTO
    {
        $self           = new self();
        $self->type     = (int)$request->input('type');
        $self->clientId = (int)$request->input('clientId');
        $self->file     = $request->file('file');

        return $self;
    }
}
