<?php

declare(strict_types=1);

namespace App\Module\Planning\Providers;

use App\Module\Planning\Commands\AssignCourierToContainerCommand;
use App\Module\Planning\Commands\AssignCourierToContainersCommand;
use App\Module\Planning\Commands\AttachInvoicesToContainerCommand;
use App\Module\Planning\Commands\ChangeContainerStatusCommand;
use App\Module\Planning\Commands\CreateContainerCommand;
use App\Module\Planning\Commands\CreateContainerFromRoutingCommand;
use App\Module\Planning\Commands\DeleteContainerCommand;
use App\Module\Planning\Commands\DeleteContainerInvoiceCommand;
use App\Module\Planning\Commands\DeleteContainerInvoicesCommand;
use App\Module\Planning\Commands\DetachInvoicesFromContainerCommand;
use App\Module\Planning\Commands\GenerateSectorContainersCommand;
use App\Module\Planning\Commands\GenerateWaveContainersCommand;
use App\Module\Planning\Commands\CreateContainerInvoicesCommand;
use App\Module\Planning\Commands\PartiallyAssembledSendEmailCommand;
use App\Module\Planning\Commands\PartiallyAssembledSendNotificationCommand;
use App\Module\Planning\Commands\SendContainersToAssemblyCommand;
use App\Module\Planning\Commands\UpdateContainerInvoiceStatusesCommand;
use App\Module\Planning\Commands\UpdateContainerNumberCommand;
use App\Module\Planning\Commands\UpdateContainerStatusCommand;
use App\Module\Planning\Commands\UpdateInvoicePlaceQuantityCommand;
use App\Module\Planning\Handlers\AssignCourierToContainerHandler;
use App\Module\Planning\Handlers\AssignCourierToContainersHandler;
use App\Module\Planning\Handlers\AttachInvoicesToContainerHandler;
use App\Module\Planning\Handlers\ChangeContainerStatusHandler;
use App\Module\Planning\Handlers\CreateContainerFromRoutingHandler;
use App\Module\Planning\Handlers\CreateContainerHandler;
use App\Module\Planning\Handlers\DeleteContainerHandler;
use App\Module\Planning\Handlers\DeleteContainerInvoiceHandler;
use App\Module\Planning\Handlers\DeleteContainerInvoicesHandler;
use App\Module\Planning\Handlers\DetachInvoicesFromContainerHandler;
use App\Module\Planning\Handlers\GenerateSectorContainersHandler;
use App\Module\Planning\Handlers\GenerateWaveContainersHandler;
use App\Module\Planning\Handlers\CreateContainerInvoicesHandler;
use App\Module\Planning\Handlers\PartiallyAssembledSendEmailHandler;
use App\Module\Planning\Handlers\PartiallyAssembledSendNotificationHandler;
use App\Module\Planning\Handlers\SendContainersToAssemblyHandler;
use App\Module\Planning\Handlers\UpdateContainerInvoiceStatusesHandler;
use App\Module\Planning\Handlers\UpdateContainerNumberHandler;
use App\Module\Planning\Handlers\UpdateContainerStatusHandler;
use App\Module\Planning\Handlers\UpdateInvoicePlaceQuantityHandler;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\ServiceProvider;

final class CommandBusServiceProviders extends ServiceProvider
{
    public function boot(): void
    {
        Bus::map([
            GenerateWaveContainersCommand::class             => GenerateWaveContainersHandler::class,
            GenerateSectorContainersCommand::class           => GenerateSectorContainersHandler::class,
            CreateContainerCommand::class                    => CreateContainerHandler::class,
            CreateContainerInvoicesCommand::class            => CreateContainerInvoicesHandler::class,
            AttachInvoicesToContainerCommand::class          => AttachInvoicesToContainerHandler::class,
            DeleteContainerCommand::class                    => DeleteContainerHandler::class,
            DetachInvoicesFromContainerCommand::class        => DetachInvoicesFromContainerHandler::class,
            AssignCourierToContainersCommand::class          => AssignCourierToContainersHandler::class,
            AssignCourierToContainerCommand::class           => AssignCourierToContainerHandler::class,
            ChangeContainerStatusCommand::class              => ChangeContainerStatusHandler::class,
            UpdateContainerInvoiceStatusesCommand::class     => UpdateContainerInvoiceStatusesHandler::class,
            SendContainersToAssemblyCommand::class           => SendContainersToAssemblyHandler::class,
            UpdateContainerStatusCommand::class              => UpdateContainerStatusHandler::class,
            UpdateInvoicePlaceQuantityCommand::class         => UpdateInvoicePlaceQuantityHandler::class,
            PartiallyAssembledSendEmailCommand::class        => PartiallyAssembledSendEmailHandler::class,
            PartiallyAssembledSendNotificationCommand::class => PartiallyAssembledSendNotificationHandler::class,
            DeleteContainerInvoiceCommand::class             => DeleteContainerInvoiceHandler::class,
            DeleteContainerInvoicesCommand::class            => DeleteContainerInvoicesHandler::class,
            UpdateContainerNumberCommand::class              => UpdateContainerNumberHandler::class,
            CreateContainerFromRoutingCommand::class         => CreateContainerFromRoutingHandler::class
        ]);
    }
}
