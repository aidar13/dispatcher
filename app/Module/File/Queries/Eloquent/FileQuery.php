<?php

declare(strict_types=1);

namespace App\Module\File\Queries\Eloquent;

use App\Module\File\Contracts\Queries\FileQuery as FileQueryContract;
use App\Module\File\Models\File;
use DomainException;

final class FileQuery implements FileQueryContract
{
    public function findById(int $id): File
    {
        $file = File::find($id);

        if (!$file) {
            throw new DomainException('Файл не найден!');
        }

        return $file;
    }

    public function findByPath(string $path): File
    {
        return File::where('path', $path)->firstOrFail();
    }
}
