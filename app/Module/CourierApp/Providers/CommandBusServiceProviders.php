<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Providers;

use App\Module\CourierApp\Commands\CarOccupancy\CreateCarOccupancyCommand;
use App\Module\CourierApp\Commands\CourierCall\CreateCourierCallCommand;
use App\Module\CourierApp\Commands\CourierLocation\CreateCourierLocationCommand;
use App\Module\CourierApp\Commands\CourierPayment\SaveCourierPaymentFilesCommand;
use App\Module\CourierApp\Commands\CourierState\CreateCourierStateCommand;
use App\Module\CourierApp\Commands\Delivery\ApproveDeliveryByInvoiceIdCommand;
use App\Module\CourierApp\Commands\Delivery\ApproveDeliveryCommand;
use App\Module\CourierApp\Commands\Delivery\ApproveDeliveryFromProviderCommand;
use App\Module\CourierApp\Commands\Delivery\CalculateCarOccupancyCommand;
use App\Module\CourierApp\Commands\Delivery\IntegrationOneC\ChangeDeliveryStatusInOneCCommand;
use App\Module\CourierApp\Commands\Delivery\SetDeliveryInfoWaitListStatusCommand;
use App\Module\CourierApp\Commands\OrderTake\ApproveOrderTakeCommand;
use App\Module\CourierApp\Commands\OrderTake\CancelTakeByInvoiceIdCommand;
use App\Module\CourierApp\Commands\OrderTake\IntegrationOneC\ChangeOrderTakeStatusInOneCCommand;
use App\Module\CourierApp\Commands\OrderTake\MassApproveTakesByInvoiceNumbersCommand;
use App\Module\CourierApp\Commands\OrderTake\SaveCourierShortcomingFilesCommand;
use App\Module\CourierApp\Commands\OrderTake\SaveInvoiceCargoPackCodeCommand;
use App\Module\CourierApp\Commands\OrderTake\SendEmailShortcomingFilesSavedCommand;
use App\Module\CourierApp\Commands\OrderTake\SetOrderTakeInfoWaitListStatusCommand;
use App\Module\CourierApp\Commands\OrderTake\SetTakeInfoWaitListStatusCommand;
use App\Module\CourierApp\Handlers\CarOccupancy\CreateCarOccupancyHandler;
use App\Module\CourierApp\Handlers\CourierCall\CreateCourierCallHandler;
use App\Module\CourierApp\Handlers\CourierLocation\CreateCourierLocationHandler;
use App\Module\CourierApp\Handlers\CourierPayment\SaveCourierPaymentFilesHandler;
use App\Module\CourierApp\Handlers\CourierState\CreateCourierStateHandler;
use App\Module\CourierApp\Handlers\Delivery\ApproveDeliveryByInvoiceIdHandler;
use App\Module\CourierApp\Handlers\Delivery\ApproveDeliveryFromProviderHandler;
use App\Module\CourierApp\Handlers\Delivery\ApproveDeliveryHandler;
use App\Module\CourierApp\Handlers\Delivery\CalculateCarOccupancyHandler;
use App\Module\CourierApp\Handlers\Delivery\IntegrationOneC\ChangeDeliveryStatusInOneCHandler;
use App\Module\CourierApp\Handlers\Delivery\SetDeliveryInfoWaitListStatusHandler;
use App\Module\CourierApp\Handlers\OrderTake\ApproveOrderTakeHandler;
use App\Module\CourierApp\Handlers\OrderTake\CancelTakeByInvoiceIdHandler;
use App\Module\CourierApp\Handlers\OrderTake\IntegrationOneC\ChangeOrderTakeStatusInOneCHandler;
use App\Module\CourierApp\Handlers\OrderTake\MassApproveTakesByInvoiceNumbersHandler;
use App\Module\CourierApp\Handlers\OrderTake\SaveCourierShortcomingFilesHandler;
use App\Module\CourierApp\Handlers\OrderTake\SaveInvoiceCargoPackCodeHandler;
use App\Module\CourierApp\Handlers\OrderTake\SendEmailShortcomingFilesSavedHandler;
use App\Module\CourierApp\Handlers\OrderTake\SetOrderTakeInfoWaitListStatusHandler;
use App\Module\CourierApp\Handlers\OrderTake\SetTakeInfoWaitListStatusHandler;
use App\Module\CRM\Commands\CreateDeliveryClientsDealsCommand;
use App\Module\CRM\Handlers\CreateDeliveryClientsDealsHandler;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\ServiceProvider;

final class CommandBusServiceProviders extends ServiceProvider
{
    private array $maps = [
        //order-take
        MassApproveTakesByInvoiceNumbersCommand::class => MassApproveTakesByInvoiceNumbersHandler::class,
        ApproveOrderTakeCommand::class                 => ApproveOrderTakeHandler::class,
        ChangeOrderTakeStatusInOneCCommand::class      => ChangeOrderTakeStatusInOneCHandler::class,
        SaveCourierShortcomingFilesCommand::class      => SaveCourierShortcomingFilesHandler::class,
        SendEmailShortcomingFilesSavedCommand::class   => SendEmailShortcomingFilesSavedHandler::class,
        CancelTakeByInvoiceIdCommand::class            => CancelTakeByInvoiceIdHandler::class,
        SetOrderTakeInfoWaitListStatusCommand::class   => SetOrderTakeInfoWaitListStatusHandler::class,
        SetTakeInfoWaitListStatusCommand::class        => SetTakeInfoWaitListStatusHandler::class,
        SaveInvoiceCargoPackCodeCommand::class         => SaveInvoiceCargoPackCodeHandler::class,

        //delivery
        ApproveDeliveryCommand::class                  => ApproveDeliveryHandler::class,
        ChangeDeliveryStatusInOneCCommand::class       => ChangeDeliveryStatusInOneCHandler::class,
        SetDeliveryInfoWaitListStatusCommand::class    => SetDeliveryInfoWaitListStatusHandler::class,
        CreateDeliveryClientsDealsCommand::class       => CreateDeliveryClientsDealsHandler::class,
        ApproveDeliveryFromProviderCommand::class      => ApproveDeliveryFromProviderHandler::class,
        ApproveDeliveryByInvoiceIdCommand::class       => ApproveDeliveryByInvoiceIdHandler::class,

        //courier-state
        CreateCourierStateCommand::class               => CreateCourierStateHandler::class,

        //courier-location
        CreateCourierLocationCommand::class            => CreateCourierLocationHandler::class,

        //courier-payment
        SaveCourierPaymentFilesCommand::class          => SaveCourierPaymentFilesHandler::class,

        //courier-call
        CreateCourierCallCommand::class                => CreateCourierCallHandler::class,

        //car-occupancy
        CreateCarOccupancyCommand::class               => CreateCarOccupancyHandler::class,
        CalculateCarOccupancyCommand::class            => CalculateCarOccupancyHandler::class,
    ];

    public function boot(): void
    {
        $this->registerCommandHandlers();
    }

    private function registerCommandHandlers(): void
    {
        Bus::map($this->maps);
    }
}
