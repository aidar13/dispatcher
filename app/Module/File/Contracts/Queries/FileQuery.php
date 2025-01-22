<?php

declare(strict_types=1);

namespace App\Module\File\Contracts\Queries;

use App\Module\File\Models\File;

interface FileQuery
{
    public function findById(int $id): File;

    public function findByPath(string $path): File;
}
