<?php

declare(strict_types=1);

namespace App\Module\Delivery\Providers;

use App\Module\Delivery\Commands\CreateDeliveryCommand;
use App\Module\Delivery\Commands\CreateReturnDeliveryCommand;
use App\Module\Delivery\Commands\CreateRouteSheetCommand;
use App\Module\Delivery\Commands\CreateRouteSheetFrom1CCommand;
use App\Module\Delivery\Commands\CreateRouteSheetInvoiceCommand;
use App\Module\Delivery\Commands\DeleteReturnDeliveryCommand;
use App\Module\Delivery\Commands\SendRouteSheetToCabinetCommand;
use App\Module\Delivery\Commands\SetDeliveryWaitListCommand;
use App\Module\Delivery\Commands\SetStatusToDeliveryByInvoiceIdCommand;
use App\Module\Delivery\Commands\SetStatusToDeliveryCommand;
use App\Module\Delivery\Commands\UpdateCustomerSectorWithReceiverIdCommand;
use App\Module\Delivery\Commands\UpdateDeliveryCommand;
use App\Module\Delivery\Commands\UpdateRouteSheetCommand;
use App\Module\Delivery\Handlers\CreateDeliveryHandler;
use App\Module\Delivery\Handlers\CreateReturnDeliveryHandler;
use App\Module\Delivery\Handlers\CreateRouteSheetFrom1CHandler;
use App\Module\Delivery\Handlers\CreateRouteSheetHandler;
use App\Module\Delivery\Handlers\CreateRouteSheetInvoiceHandler;
use App\Module\Delivery\Handlers\DeleteReturnDeliveryHandler;
use App\Module\Delivery\Handlers\SendRouteSheetToCabinetHandler;
use App\Module\Delivery\Handlers\SetDeliveryWaitListHandler;
use App\Module\Delivery\Handlers\SetStatusToDeliveryByInvoiceIdHandler;
use App\Module\Delivery\Handlers\SetStatusToDeliveryHandler;
use App\Module\Delivery\Handlers\UpdateCustomerSectorWithReceiverIdHandler;
use App\Module\Delivery\Handlers\UpdateDeliveryHandler;
use App\Module\Delivery\Handlers\UpdateRouteSheetHandler;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\ServiceProvider;

final class CommandBusServiceProviders extends ServiceProvider
{
    public function boot(): void
    {
        Bus::map([
            CreateDeliveryCommand::class                     => CreateDeliveryHandler::class,
            UpdateDeliveryCommand::class                     => UpdateDeliveryHandler::class,
            SetDeliveryWaitListCommand::class                => SetDeliveryWaitListHandler::class,
            SetStatusToDeliveryCommand::class                => SetStatusToDeliveryHandler::class,
            CreateReturnDeliveryCommand::class               => CreateReturnDeliveryHandler::class,
            DeleteReturnDeliveryCommand::class               => DeleteReturnDeliveryHandler::class,
            UpdateCustomerSectorWithReceiverIdCommand::class => UpdateCustomerSectorWithReceiverIdHandler::class,
            SetStatusToDeliveryByInvoiceIdCommand::class     => SetStatusToDeliveryByInvoiceIdHandler::class,

            //Route Sheet
            CreateRouteSheetCommand::class                   => CreateRouteSheetHandler::class,
            CreateRouteSheetFrom1CCommand::class             => CreateRouteSheetFrom1CHandler::class,
            UpdateRouteSheetCommand::class                   => UpdateRouteSheetHandler::class,
            CreateRouteSheetInvoiceCommand::class            => CreateRouteSheetInvoiceHandler::class,
            SendRouteSheetToCabinetCommand::class            => SendRouteSheetToCabinetHandler::class,
        ]);
    }
}
