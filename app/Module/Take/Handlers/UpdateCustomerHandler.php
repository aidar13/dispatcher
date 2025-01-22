<?php

declare(strict_types=1);

namespace App\Module\Take\Handlers;

use App\Module\DispatcherSector\Contracts\Queries\DispatcherSectorPolygonQuery;
use App\Module\DispatcherSector\Contracts\Queries\SectorPolygonQuery;
use App\Module\Take\Commands\UpdateCustomerCommand;
use App\Module\Take\Contracts\Queries\CustomerQuery;
use App\Module\Take\Contracts\Repositories\UpdateCustomerRepository;
use Illuminate\Contracts\Container\BindingResolutionException;

final class UpdateCustomerHandler
{
    public function __construct(
        private readonly CustomerQuery $customerQuery,
        private readonly UpdateCustomerRepository $customerRepository,
    ) {
    }

    /**
     * @throws BindingResolutionException
     */
    public function handle(UpdateCustomerCommand $command): void
    {
        $customer = $this->customerQuery->getById($command->id);

        if (!$customer) {
            return;
        }

        $customer->full_name            = $command->DTO->fullName;
        $customer->address              = $command->DTO->address;
        $customer->phone                = $command->DTO->phone;
        $customer->additional_phone     = $command->DTO->additionalPhone;
        $customer->latitude             = $command->DTO->latitude;
        $customer->longitude            = $command->DTO->longitude;
        $customer->sector_id            = $this->getSectorId($command);
        $customer->dispatcher_sector_id = $this->getDispatcherSectorId($command);

        $this->customerRepository->update($customer);
    }

    /**
     * @throws BindingResolutionException
     */
    private function getSectorId(UpdateCustomerCommand $command): ?int
    {
        /** @var SectorPolygonQuery $query */
        $query  = app()->make(SectorPolygonQuery::class);
        $sector = $query->findByCoordinates($command->DTO->latitude, $command->DTO->longitude);

        return $sector?->id;
    }

    /**
     * @throws BindingResolutionException
     */
    private function getDispatcherSectorId(UpdateCustomerCommand $command): ?int
    {
        /** @var DispatcherSectorPolygonQuery $query */
        $query  = app()->make(DispatcherSectorPolygonQuery::class);
        $sector = $query->findByCoordinates($command->DTO->latitude, $command->DTO->longitude);

        return $sector?->id;
    }
}
