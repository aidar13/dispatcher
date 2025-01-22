<?php

declare(strict_types=1);

namespace App\Module\Take\Handlers;

use App\Module\DispatcherSector\Contracts\Queries\DispatcherSectorPolygonQuery;
use App\Module\DispatcherSector\Contracts\Queries\SectorPolygonQuery;
use App\Module\Take\Commands\CreateCustomerCommand;
use App\Module\Take\Contracts\Repositories\CreateCustomerRepository;
use App\Module\Take\Models\Customer;
use Illuminate\Contracts\Container\BindingResolutionException;

final readonly class CreateCustomerHandler
{
    public function __construct(
        private CreateCustomerRepository $customerRepository,
    ) {
    }

    /**
     * @throws BindingResolutionException
     */
    public function handle(CreateCustomerCommand $command): Customer
    {
        $customer                       = new Customer();
        $customer->full_name            = $command->DTO->fullName;
        $customer->address              = $command->DTO->address;
        $customer->phone                = $command->DTO->phone;
        $customer->additional_phone     = $command->DTO->additionalPhone;
        $customer->latitude             = $command->DTO->latitude;
        $customer->longitude            = $command->DTO->longitude;
        $customer->sector_id            = $this->getSectorId($command);
        $customer->dispatcher_sector_id = $this->getDispatcherSectorId($command);

        $this->customerRepository->create($customer);

        return $customer;
    }

    /**
     * @throws BindingResolutionException
     */
    private function getSectorId(CreateCustomerCommand $command): ?int
    {
        /** @var SectorPolygonQuery $query */
        $query  = app()->make(SectorPolygonQuery::class);
        $sector = $query->findByCoordinates($command->DTO->latitude, $command->DTO->longitude);

        return $sector?->id ?? $command->sectorId;
    }

    /**
     * @throws BindingResolutionException
     */
    private function getDispatcherSectorId(CreateCustomerCommand $command): ?int
    {
        /** @var DispatcherSectorPolygonQuery $query */
        $query  = app()->make(DispatcherSectorPolygonQuery::class);
        $sector = $query->findByCoordinates($command->DTO->latitude, $command->DTO->longitude);

        return $sector?->id ?? $command->dispatcherSectorId;
    }
}
