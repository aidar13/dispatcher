<?php

declare(strict_types=1);

namespace App\Module\File\Contracts\Repositories;

use App\Module\File\Models\File;

interface CreateFileRepository
{
    public function create(File $model): void;
}
