<?php

declare(strict_types=1);

namespace App\Module\Courier\Handlers;

use App\Exceptions\DomainExceptionWithErrors;
use App\Module\Courier\Commands\UpdateCourierPhoneCommand;
use App\Module\Courier\Contracts\Queries\CourierQuery;
use App\Module\Courier\Contracts\Repositories\UpdateCourierRepository;
use App\Module\Courier\Events\CourierUpdatedEvent;
use App\Module\Gateway\Contracts\GatewayUserQuery;
use App\ValueObjects\PhoneNumber;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final readonly class UpdateCourierPhoneHandler
{
    public function __construct(
        private GatewayUserQuery $gatewayUserQuery,
        private CourierQuery $courierQuery,
        private UpdateCourierRepository $updateCourierRepository
    ) {
    }

    /**
     * @throws DomainExceptionWithErrors
     */
    public function handle(UpdateCourierPhoneCommand $command): void
    {
        $courier = $this->courierQuery->getById($command->id);
        $phone   = new PhoneNumber($command->phoneNumber);

        try {
            DB::beginTransaction();

            if ($this->gatewayUserQuery->hasPhoneNumber($phone)) {
                throw new DomainExceptionWithErrors('Пользователь с таким номером уже сущестует!');
            }

            $courier->phone_number = str_replace('+', '', $phone->getPhone());

            $this->updateCourierRepository->update($courier);

            event(new CourierUpdatedEvent($courier->id));

            DB::commit();
        } catch (Throwable $exception) {
            DB::rollBack();

            Log::info('Не удалось поменять номер телефона! ', [
                'courierId'   => $courier->id,
                'phoneNumber' => $command->phoneNumber,
                'message'     => $exception->getMessage()
            ]);

            throw new DomainExceptionWithErrors('Не удалось поменять номер телефона! ' . $exception->getMessage());
        }
    }
}
