<?php

declare(strict_types=1);

namespace App\Module\City\Handlers;

use App\Module\City\Commands\CreateRegionCommand;
use App\Module\City\Contracts\Queries\RegionQuery;
use App\Module\City\Contracts\Repositories\CreateRegionRepository;
use App\Module\City\Models\Region;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class CreateRegionHandler
{
    public function __construct(
        private readonly CreateRegionRepository $createRegionRepository,
        private readonly RegionQuery $regionQuery
    ) {
    }

    public function handle(CreateRegionCommand $command): void
    {
        if (!$this->regionExists($command->DTO->id)) {
            $region = new Region();

            $region->id         = $command->DTO->id;
            $region->name       = $command->DTO->name;
            $region->country_id = $command->DTO->countryId;

            $this->createRegionRepository->create($region);
        }
    }

    private function regionExists(int $id): bool
    {
        try {
            $this->regionQuery->getById($id);

            return true;
        } catch (ModelNotFoundException) {
            return false;
        }
    }
}
