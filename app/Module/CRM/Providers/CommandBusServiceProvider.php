<?php

declare(strict_types=1);

namespace App\Module\CRM\Providers;

use App\Module\CRM\Commands\CreateDeliveryClientsDealsCommand;
use App\Module\CRM\Commands\CreateTakeClientsDealsCommand;
use App\Module\CRM\Handlers\CreateDeliveryClientsDealsHandler;
use App\Module\CRM\Handlers\CreateTakeClientsDealsHandler;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\ServiceProvider;

class CommandBusServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerCommandHandlers();
    }

    /**
     * @return void
     */
    private function registerCommandHandlers(): void
    {
        Bus::map(array(
            CreateDeliveryClientsDealsCommand::class => CreateDeliveryClientsDealsHandler::class,
            CreateTakeClientsDealsCommand::class     => CreateTakeClientsDealsHandler::class
        ));
    }
}
