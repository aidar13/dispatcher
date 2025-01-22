<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Module\Delivery\Models\RouteSheet;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class SetDispatcherSectorIdCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'route-sheet:set-dispatcher-sector-id';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set dispatcher_sector_id in route_sheets table';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $models = RouteSheet::query()->with('courier');
        $count  = $models->count();

        if ($count === 0) {
            $this->info("No records found.");
            return 0;
        }

        $this->info("Found {$count} records");
        $progressBar = $this->output->createProgressBar($count);

        $models->chunk(1000, function (Collection $collection) use ($progressBar, &$count) {
            /** @var RouteSheet $model */
            foreach ($collection as $model) {
                try {
                    $model->setDispatcherSectorId($model?->courier?->dispatcher_sector_id);
                    $model->save();

                    $progressBar->advance();
                } catch (\Throwable $e) {
                    $count--;
                    $this->error("Failed to process RouteSheet with ID: {$model->id}. {$e->getMessage()}");
                }
            }
        });

        $progressBar->finish();
        $this->info(PHP_EOL);
        $this->info("{$count} records have been successfully saved.");

        return 0;
    }
}
