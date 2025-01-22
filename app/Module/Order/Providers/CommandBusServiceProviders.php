<?php

declare(strict_types=1);

namespace App\Module\Order\Providers;

use App\Module\Order\Commands\ChangeInvoiceTakeDateCommand;
use App\Module\Order\Commands\CreateAdditionalServiceValueCommand;
use App\Module\Order\Commands\CreateFastDeliveryOrderByContainerCommand;
use App\Module\Order\Commands\CreateFastDeliveryOrderCommand;
use App\Module\Order\Commands\CreateInvoiceCargoCommand;
use App\Module\Order\Commands\CreateInvoiceCommand;
use App\Module\Order\Commands\CreateOrderCommand;
use App\Module\Order\Commands\CreateReceiverCommand;
use App\Module\Order\Commands\CreateSenderCommand;
use App\Module\Order\Commands\CreateSlaCommand;
use App\Module\Order\Commands\DeleteAdditionalServiceValueCommand;
use App\Module\Order\Commands\Integration\CancelInvoiceInCabinetCommand;
use App\Module\Order\Commands\Integration\UpdateInvoiceSectorsInCabinetCommand;
use App\Module\Order\Commands\IntegrationChangeTakeDateByOrderCommand;
use App\Module\Order\Commands\ResendDeliveryStatusToOneCByInvoiceIdCommand;
use App\Module\Order\Commands\SetFastDeliveryCourierCommand;
use App\Module\Order\Commands\SetInvoiceCargoTypeCommand;
use App\Module\Order\Commands\SetInvoiceSectorsCommand;
use App\Module\Order\Commands\SetInvoicesWaveCommand;
use App\Module\Order\Commands\SetInvoiceWaitListIdCommand;
use App\Module\Order\Commands\SetInvoiceWaveCommand;
use App\Module\Order\Commands\SetReceiverDispatcherSectorCommand;
use App\Module\Order\Commands\SetSenderDispatcherSectorCommand;
use App\Module\Order\Commands\UpdateAdditionalServiceValueCommand;
use App\Module\Order\Commands\UpdateFastDeliveryOrderCommand;
use App\Module\Order\Commands\UpdateInvoiceCargoCommand;
use App\Module\Order\Commands\UpdateInvoiceCommand;
use App\Module\Order\Commands\UpdateInvoiceDeliveryDateCommand;
use App\Module\Order\Commands\UpdateInvoiceSlaCommand;
use App\Module\Order\Commands\UpdateOrderCommand;
use App\Module\Order\Commands\UpdateReceiverCommand;
use App\Module\Order\Commands\UpdateSenderCommand;
use App\Module\Order\Commands\UpdateSlaCommand;
use App\Module\Order\Handlers\ChangeInvoiceTakeDateHandler;
use App\Module\Order\Handlers\CreateAdditionalServiceValueHandler;
use App\Module\Order\Handlers\CreateFastDeliveryOrderHandler;
use App\Module\Order\Handlers\CreateInvoiceCargoHandler;
use App\Module\Order\Handlers\CreateInvoiceHandler;
use App\Module\Order\Handlers\CreateOrderHandler;
use App\Module\Order\Handlers\CreateReceiverHandler;
use App\Module\Order\Handlers\CreateSenderHandler;
use App\Module\Order\Handlers\CreateSlaHandler;
use App\Module\Order\Handlers\DeleteAdditionalServiceValueHandler;
use App\Module\Order\Handlers\Integration\CancelInvoiceInCabinetHandler;
use App\Module\Order\Handlers\Integration\CreateFastDeliveryOrderByContainerHandler;
use App\Module\Order\Handlers\Integration\UpdateInvoiceSectorsInCabinetHandler;
use App\Module\Order\Handlers\IntegrationChangeTakeDateByOrderHandler;
use App\Module\Order\Handlers\ResendDeliveryStatusToOneCByInvoiceIdHandler;
use App\Module\Order\Handlers\SetFastDeliveryCourierHandler;
use App\Module\Order\Handlers\SetInvoiceCargoTypeHandler;
use App\Module\Order\Handlers\SetInvoiceSectorsHandler;
use App\Module\Order\Handlers\SetInvoicesWaveHandler;
use App\Module\Order\Handlers\SetInvoiceWaitListIdHandler;
use App\Module\Order\Handlers\SetInvoiceWaveHandler;
use App\Module\Order\Handlers\SetReceiverDispatcherSectorHandler;
use App\Module\Order\Handlers\SetSenderDispatcherSectorHandler;
use App\Module\Order\Handlers\UpdateAdditionalServiceValueHandler;
use App\Module\Order\Handlers\UpdateFastDeliveryOrderHandler;
use App\Module\Order\Handlers\UpdateInvoiceCargoHandler;
use App\Module\Order\Handlers\UpdateInvoiceDeliveryDateHandler;
use App\Module\Order\Handlers\UpdateInvoiceHandler;
use App\Module\Order\Handlers\UpdateInvoiceSlaHandler;
use App\Module\Order\Handlers\UpdateOrderHandler;
use App\Module\Order\Handlers\UpdateReceiverHandler;
use App\Module\Order\Handlers\UpdateSenderHandler;
use App\Module\Order\Handlers\UpdateSlaHandler;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\ServiceProvider;

final class CommandBusServiceProviders extends ServiceProvider
{
    public function boot(): void
    {
        Bus::map(array(
            CreateOrderCommand::class                           => CreateOrderHandler::class,
            CreateSenderCommand::class                          => CreateSenderHandler::class,
            CreateReceiverCommand::class                        => CreateReceiverHandler::class,
            CreateInvoiceCommand::class                         => CreateInvoiceHandler::class,
            CreateInvoiceCargoCommand::class                    => CreateInvoiceCargoHandler::class,
            CreateSlaCommand::class                             => CreateSlaHandler::class,
            UpdateSlaCommand::class                             => UpdateSlaHandler::class,
            CreateAdditionalServiceValueCommand::class          => CreateAdditionalServiceValueHandler::class,
            UpdateAdditionalServiceValueCommand::class          => UpdateAdditionalServiceValueHandler::class,
            DeleteAdditionalServiceValueCommand::class          => DeleteAdditionalServiceValueHandler::class,
            UpdateSenderCommand::class                          => UpdateSenderHandler::class,
            UpdateOrderCommand::class                           => UpdateOrderHandler::class,
            UpdateReceiverCommand::class                        => UpdateReceiverHandler::class,
            UpdateInvoiceCommand::class                         => UpdateInvoiceHandler::class,
            UpdateInvoiceCargoCommand::class                    => UpdateInvoiceCargoHandler::class,
            SetSenderDispatcherSectorCommand::class             => SetSenderDispatcherSectorHandler::class,
            SetReceiverDispatcherSectorCommand::class           => SetReceiverDispatcherSectorHandler::class,
            SetInvoiceWaveCommand::class                        => SetInvoiceWaveHandler::class,
            UpdateInvoiceSlaCommand::class                      => UpdateInvoiceSlaHandler::class,
            UpdateInvoiceDeliveryDateCommand::class             => UpdateInvoiceDeliveryDateHandler::class,
            SetInvoiceCargoTypeCommand::class                   => SetInvoiceCargoTypeHandler::class,
            SetInvoiceWaitListIdCommand::class                  => SetInvoiceWaitListIdHandler::class,
            SetInvoiceSectorsCommand::class                     => SetInvoiceSectorsHandler::class,
            SetInvoicesWaveCommand::class                       => SetInvoicesWaveHandler::class,
            ResendDeliveryStatusToOneCByInvoiceIdCommand::class => ResendDeliveryStatusToOneCByInvoiceIdHandler::class,

            CreateFastDeliveryOrderCommand::class            => CreateFastDeliveryOrderHandler::class,
            UpdateFastDeliveryOrderCommand::class            => UpdateFastDeliveryOrderHandler::class,
            SetFastDeliveryCourierCommand::class             => SetFastDeliveryCourierHandler::class,
            CreateFastDeliveryOrderByContainerCommand::class => CreateFastDeliveryOrderByContainerHandler::class,
            IntegrationChangeTakeDateByOrderCommand::class   => IntegrationChangeTakeDateByOrderHandler::class,

            // IntegrationOneC
            ChangeInvoiceTakeDateCommand::class              => ChangeInvoiceTakeDateHandler::class,
            CancelInvoiceInCabinetCommand::class             => CancelInvoiceInCabinetHandler::class,
            UpdateInvoiceSectorsInCabinetCommand::class      => UpdateInvoiceSectorsInCabinetHandler::class,
        ));
    }
}
