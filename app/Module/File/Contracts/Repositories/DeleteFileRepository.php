<?php

declare(strict_types=1);

namespace App\Module\File\Contracts\Repositories;

interface DeleteFileRepository
{
    public function delete(string $uuidHash): void;
}
