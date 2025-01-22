<?php

namespace App\Module\File\DTO;

use App\Module\File\Requests\UploadFilesRequest;

final class UploadFilesDTO
{
    public int $type;
    public array $files;

    public static function fromRequest(UploadFilesRequest $request): UploadFilesDTO
    {
        $self        = new self();
        $self->type  = $request->input('type');
        $self->files = $request->file('files');

        return $self;
    }

    public function setType(int $type): void
    {
        $this->type = $type;
    }

    public function setFile(array $file): void
    {
        $this->files = $file;
    }
}
