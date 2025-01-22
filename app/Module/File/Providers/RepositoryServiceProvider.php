<?php

declare(strict_types=1);

namespace App\Module\File\Providers;

use App\Module\File\Contracts\Repositories\CreateFileRepository;
use App\Module\File\Contracts\Repositories\DeleteFileRepository;
use App\Module\File\Repositories\Eloquent\FileRepository;
use Illuminate\Support\ServiceProvider;

final class RepositoryServiceProvider extends ServiceProvider
{
    public array $bindings = [
        CreateFileRepository::class => FileRepository::class,
        DeleteFileRepository::class => FileRepository::class,
    ];
}
