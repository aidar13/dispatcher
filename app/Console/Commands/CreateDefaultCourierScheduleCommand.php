<?php

namespace App\Console\Commands;

use App\Module\Courier\Commands\CreateCourierScheduleCommand;
use App\Module\Courier\DTO\CourierScheduleInfoDTO;
use App\Module\Courier\DTO\CreateCourierScheduleDTO;
use App\Module\Courier\Models\Courier;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class CreateDefaultCourierScheduleCommand extends Command
{
    protected $signature = 'command:add-default-courier-schedule';

    protected $description = 'Добавляет расписание компании по бизнес модели';

    public function handle(): void
    {
        $object = Courier::query()->whereDoesntHave('schedules');
        $count  = $object->count();

        $this->info("Найдено $count");
        $progressBar = $this->output->createProgressBar($count);

        $object->chunk(100, function (Collection $collection) use ($progressBar) {
            $collection->each(function (Courier $courier) use ($progressBar) {
                try {
                    $DTO                     = new CreateCourierScheduleDTO();
                    $DTO->courierId          = $courier->id;
                    $weekday1                = new CourierScheduleInfoDTO();
                    $weekday1->weekday       = 1;
                    $weekday1->workTimeFrom  = "08:00:00";
                    $weekday1->workTimeUntil = "21:00:00";
                    $weekday2                = new CourierScheduleInfoDTO();
                    $weekday2->weekday       = 2;
                    $weekday2->workTimeFrom  = "08:00:00";
                    $weekday2->workTimeUntil = "21:00:00";
                    $weekday3                = new CourierScheduleInfoDTO();
                    $weekday3->weekday       = 3;
                    $weekday3->workTimeFrom  = "08:00:00";
                    $weekday3->workTimeUntil = "21:00:00";
                    $weekday4                = new CourierScheduleInfoDTO();
                    $weekday4->weekday       = 4;
                    $weekday4->workTimeFrom  = "08:00:00";
                    $weekday4->workTimeUntil = "21:00:00";
                    $weekday5                = new CourierScheduleInfoDTO();
                    $weekday5->weekday       = 5;
                    $weekday5->workTimeFrom  = "08:00:00";
                    $weekday5->workTimeUntil = "21:00:00";
                    $DTO->schedules          = collect([$weekday1, $weekday2, $weekday3, $weekday4, $weekday5]);
                    dispatch(new CreateCourierScheduleCommand($DTO));
                    $progressBar->advance();
                } catch (\Throwable $exception) {
                    $this->error("id=$courier->id " . $exception->getMessage());
                }
            });
        });

        $progressBar->finish();
    }
}
