<?php

declare(strict_types=1);

namespace App\Module\Courier\Handlers;

use App\Module\Courier\Commands\SaveCloseCourierDayCommand;
use App\Module\Courier\Contracts\Queries\CourierQuery;
use App\Module\Courier\Contracts\Repositories\CreateCloseCourierDayRepository;
use App\Module\Courier\Events\CloseCourierDayCreatedEvent;
use App\Module\Courier\Models\CloseCourierDay;

final class SaveCloseCourierDayHandler
{
    public function __construct(
        private readonly CourierQuery $courierQuery,
        private readonly CreateCloseCourierDayRepository $closeCourierDayRepository
    ) {
    }

    public function handle(SaveCloseCourierDayCommand $command): void
    {
        $courier = $this->courierQuery->getById($command->courierId);

        $model             = new CloseCourierDay();
        $model->courier_id = $courier->id;
        $model->user_id    = $command->userId;
        $model->date       = $command->date;

        $this->closeCourierDayRepository->create($model);

        event(new CloseCourierDayCreatedEvent($model->id));
    }
}
