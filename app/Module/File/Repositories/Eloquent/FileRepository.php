<?php

declare(strict_types=1);

namespace App\Module\File\Repositories\Eloquent;

use App\Module\File\Contracts\Repositories\CreateFileRepository;
use App\Module\File\Contracts\Repositories\DeleteFileRepository;
use App\Module\File\Models\File;
use Throwable;

final class FileRepository implements CreateFileRepository, DeleteFileRepository
{
    /**
     * @param File $model
     * @throws Throwable
     */
    public function create(File $model): void
    {
        $model->saveOrFail();
    }

    public function delete(string $uuidHash): void
    {
        $model = File::where('uuid_hash', $uuidHash)->firstOrFail();
        $model->delete();
    }
}
