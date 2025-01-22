<?php

declare(strict_types=1);

namespace Tests\Feature\Region;

use App\Module\City\Commands\CreateRegionCommand;
use App\Module\City\DTO\RegionDTO;
use App\Module\City\Models\Region;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

final class RegionTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testCreateRegion()
    {
        /** @var Region $region */
        $region = Region::factory()->make();

        $dto            = new RegionDTO();
        $dto->name      = $region->name;
        $dto->id        = $this->faker->numberBetween(0, 100);
        $dto->countryId = $region->country_id;

        dispatch(new CreateRegionCommand($dto));

        $this->assertDatabaseHas('regions', [
            'id'         => $dto->id,
            'name'       => $dto->name,
            'country_id' => $dto->countryId
        ]);
    }
}
