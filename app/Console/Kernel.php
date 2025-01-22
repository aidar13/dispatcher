<?php

namespace App\Console;

use App\Console\Commands\CheckMVRPRoutingStatusCommand;
use App\Console\Commands\CheckSVRPRoutingStatusCommand;
use App\Console\Commands\CreateMVRPYandexRoutingCommand;
use App\Console\Commands\DeleteRabbitMQRequest;
use App\Console\Commands\OrderCoordinateReport;
use App\Console\Commands\UpdateInvoiceSectors;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('events:clear')->daily();

        $schedule->command(UpdateInvoiceSectors::class)
            ->environments(['production'])
            ->everyTenMinutes();

        $schedule->command(DeleteRabbitMQRequest::class)
            ->dailyAt('00:00');

        $schedule->command(OrderCoordinateReport::class)
            ->hourly()
            ->between('7:00', '22:00');

        $schedule->command(CreateMVRPYandexRoutingCommand::class)
            ->dailyAt('06:00');

        $schedule->command(CheckMVRPRoutingStatusCommand::class)
            ->everyFiveMinutes()
            ->between('06:00', '10:00');

        $schedule->command(CheckSVRPRoutingStatusCommand::class)
            ->everyFiveMinutes()
            ->between('06:00', '22:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
