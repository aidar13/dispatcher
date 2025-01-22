<?php

declare(strict_types=1);

namespace App\Module\File\Providers;

use App\Module\File\Commands\CreateFileCommand;
use App\Module\File\Commands\DeleteFileCommand;
use App\Module\File\Commands\DeleteFileWithoutAwsCommand;
use App\Module\File\Commands\Integration\IntegrationCreateFileCommand;
use App\Module\File\Commands\UploadFileCommand;
use App\Module\File\Commands\UploadFileToAwsCommand;
use App\Module\File\Handlers\CreateFileHandler;
use App\Module\File\Handlers\DeleteFileHandler;
use App\Module\File\Handlers\DeleteFileWithoutAwsHandler;
use App\Module\File\Handlers\Integration\IntegrationCreateFileHandler;
use App\Module\File\Handlers\UploadFileHandler;
use App\Module\File\Handlers\UploadFileToAwsHandler;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\ServiceProvider;

final class CommandBusServiceProviders extends ServiceProvider
{
    public function boot(): void
    {
        Bus::map([
            CreateFileCommand::class           => CreateFileHandler::class,
            UploadFileToAwsCommand::class      => UploadFileToAwsHandler::class,
            UploadFileCommand::class           => UploadFileHandler::class,
            DeleteFileCommand::class           => DeleteFileHandler::class,
            DeleteFileWithoutAwsCommand::class => DeleteFileWithoutAwsHandler::class,

            //integration
            IntegrationCreateFileCommand::class => IntegrationCreateFileHandler::class
        ]);
    }
}
