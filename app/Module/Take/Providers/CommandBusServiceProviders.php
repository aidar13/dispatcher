<?php

declare(strict_types=1);

namespace App\Module\Take\Providers;

use App\Module\Take\Commands\AssignCourierToOrderIn1CCommand;
use App\Module\Take\Commands\AssignCourierToOrderInCabinetCommand;
use App\Module\Take\Commands\AssignCourierToTakeCargoCommand;
use App\Module\Take\Commands\AssignOrderTakesToCourierCommand;
use App\Module\Take\Commands\AssignOrderTakeToCourierCommand;
use App\Module\Take\Commands\ChangeTakeDateByInvoiceIdCommand;
use App\Module\Take\Commands\ChangeTakeDateByOrderIdCommand;
use App\Module\Take\Commands\CreateCustomerCommand;
use App\Module\Take\Commands\CreateOrderTakeCommand;
use App\Module\Take\Commands\SetStatusToTakeByInvoiceCommand;
use App\Module\Take\Commands\SetStatusToTakeCommand;
use App\Module\Take\Commands\SetTakeWaitListCommand;
use App\Module\Take\Commands\SetWaitListStatusCommand;
use App\Module\Take\Commands\UpdateCustomerCommand;
use App\Module\Take\Commands\UpdateCustomerSectorWithSenderIdCommand;
use App\Module\Take\Commands\UpdateOrderTakeCommand;
use App\Module\Take\Handlers\AssignCourierToOrderIn1CHandler;
use App\Module\Take\Handlers\AssignCourierToOrderInCabinetHandler;
use App\Module\Take\Handlers\AssignCourierToTakeCargoHandler;
use App\Module\Take\Handlers\AssignOrderTakesToCourierHandler;
use App\Module\Take\Handlers\AssignOrderTakeToCourierHandler;
use App\Module\Take\Handlers\ChangeTakeDateByInvoiceIdHandler;
use App\Module\Take\Handlers\ChangeTakeDateByOrderIdHandler;
use App\Module\Take\Handlers\CreateCustomerHandler;
use App\Module\Take\Handlers\CreateOrderTakeHandler;
use App\Module\Take\Handlers\SetStatusToTakeByInvoiceHandler;
use App\Module\Take\Handlers\SetStatusToTakeHandler;
use App\Module\Take\Handlers\SetTakeWaitListHandler;
use App\Module\Take\Handlers\SetWaitListStatusHandler;
use App\Module\Take\Handlers\UpdateCustomerHandler;
use App\Module\Take\Handlers\UpdateCustomerSectorWithSenderIdHandler;
use App\Module\Take\Handlers\UpdateOrderTakeHandler;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\ServiceProvider;

final class CommandBusServiceProviders extends ServiceProvider
{
    public function boot(): void
    {
        Bus::map(array(
            CreateOrderTakeCommand::class                  => CreateOrderTakeHandler::class,
            UpdateOrderTakeCommand::class                  => UpdateOrderTakeHandler::class,
            CreateCustomerCommand::class                   => CreateCustomerHandler::class,
            UpdateCustomerCommand::class                   => UpdateCustomerHandler::class,
            AssignOrderTakesToCourierCommand::class        => AssignOrderTakesToCourierHandler::class,
            AssignOrderTakeToCourierCommand::class         => AssignOrderTakeToCourierHandler::class,
            AssignCourierToTakeCargoCommand::class         => AssignCourierToTakeCargoHandler::class,
            AssignCourierToOrderIn1CCommand::class         => AssignCourierToOrderIn1CHandler::class,
            ChangeTakeDateByOrderIdCommand::class          => ChangeTakeDateByOrderIdHandler::class,
            ChangeTakeDateByInvoiceIdCommand::class        => ChangeTakeDateByInvoiceIdHandler::class,
            SetTakeWaitListCommand::class                  => SetTakeWaitListHandler::class,
            SetStatusToTakeCommand::class                  => SetStatusToTakeHandler::class,
            AssignCourierToOrderInCabinetCommand::class    => AssignCourierToOrderInCabinetHandler::class,
            UpdateCustomerSectorWithSenderIdCommand::class => UpdateCustomerSectorWithSenderIdHandler::class,
            SetStatusToTakeByInvoiceCommand::class         => SetStatusToTakeByInvoiceHandler::class,
            SetWaitListStatusCommand::class                => SetWaitListStatusHandler::class,
        ));
    }
}
