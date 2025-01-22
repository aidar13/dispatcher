<?php

declare(strict_types=1);

namespace App\Module\Courier\Providers;

use App\Module\Courier\Commands\CloseCourierDayCommand;
use App\Module\Courier\Commands\CreateCourierScheduleCommand;
use App\Module\Courier\Commands\CreateCourierStopCommand;
use App\Module\Courier\Commands\Integration\CreateCourierCommand as IntegrationCreateCourierCommand;
use App\Module\Courier\Commands\Integration\CreateCourierLicensesCommand;
use App\Module\Courier\Commands\Integration\CreateCourierPaymentCommand as IntegrationCreateCourierPaymentCommand;
use App\Module\Courier\Commands\Integration\CreateCourierStopCommand as IntegrationCreateCourierStopCommand;
use App\Module\Courier\Commands\Integration\UpdateCourierCommand as IntegrationUpdateCourierCommand;
use App\Module\Courier\Commands\Integration\UpdateCourierLicensesCommand;
use App\Module\Courier\Commands\SaveCloseCourierDayCommand;
use App\Module\Courier\Commands\UpdateCourierCommand;
use App\Module\Courier\Commands\UpdateCourierPhoneCommand;
use App\Module\Courier\Commands\UpdateCourierPhoneNumberInGatewayCommand;
use App\Module\Courier\Commands\UpdateCourierRoutingCommand;
use App\Module\Courier\Commands\UpLoadCourierFilesCommand;
use App\Module\Courier\Handlers\CloseCourierDayHandler;
use App\Module\Courier\Handlers\CreateCourierScheduleHandler;
use App\Module\Courier\Handlers\CreateCourierStopHandler;
use App\Module\Courier\Handlers\Integration\CreateCourierHandler as IntegrationCreateCourierHandler;
use App\Module\Courier\Handlers\Integration\CreateCourierLicensesHandler;
use App\Module\Courier\Handlers\Integration\CreateCourierPaymentHandler as IntegrationCreateCourierPaymentHandler;
use App\Module\Courier\Handlers\Integration\CreateCourierStopHandler as IntegrationCreateCourierStopHandler;
use App\Module\Courier\Handlers\Integration\UpdateCourierHandler as IntegrationUpdateCourierHandler;
use App\Module\Courier\Handlers\Integration\UpdateCourierLicensesHandler;
use App\Module\Courier\Handlers\SaveCloseCourierDayHandler;
use App\Module\Courier\Handlers\UpdateCourierHandler;
use App\Module\Courier\Handlers\UpdateCourierPhoneHandler;
use App\Module\Courier\Handlers\UpdateCourierPhoneNumberInGatewayHandler;
use App\Module\Courier\Handlers\UpdateCourierRoutingHandler;
use App\Module\Courier\Handlers\UpLoadCourierFilesHandler;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\ServiceProvider;

final class CommandBusServiceProviders extends ServiceProvider
{
    public function boot(): void
    {
        Bus::map(array(
            IntegrationCreateCourierCommand::class          => IntegrationCreateCourierHandler::class,
            IntegrationCreateCourierPaymentCommand::class   => IntegrationCreateCourierPaymentHandler::class,
            IntegrationUpdateCourierCommand::class          => IntegrationUpdateCourierHandler::class,
            UpdateCourierCommand::class                     => UpdateCourierHandler::class,
            IntegrationCreateCourierStopCommand::class      => IntegrationCreateCourierStopHandler::class,
            CreateCourierStopCommand::class                 => CreateCourierStopHandler::class,
            UpLoadCourierFilesCommand::class                => UpLoadCourierFilesHandler::class,
            CloseCourierDayCommand::class                   => CloseCourierDayHandler::class,
            SaveCloseCourierDayCommand::class               => SaveCloseCourierDayHandler::class,
            CreateCourierScheduleCommand::class             => CreateCourierScheduleHandler::class,
            CreateCourierLicensesCommand::class             => CreateCourierLicensesHandler::class,
            UpdateCourierLicensesCommand::class             => UpdateCourierLicensesHandler::class,
            UpdateCourierPhoneCommand::class                => UpdateCourierPhoneHandler::class,
            UpdateCourierPhoneNumberInGatewayCommand::class => UpdateCourierPhoneNumberInGatewayHandler::class,
            UpdateCourierRoutingCommand::class              => UpdateCourierRoutingHandler::class,
        ));
    }
}
