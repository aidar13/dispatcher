<?php

declare(strict_types=1);

namespace Tests\Feature\City;

use App\Module\City\Commands\CreateCityCommand;
use App\Module\City\Commands\UpdateCityCommand;
use App\Module\City\DTO\CityDTO;
use App\Module\City\Models\City;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

final class CityTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testCreateCity()
    {
        /** @var City $city */
        $city = City::factory()->make();

        $dto              = new CityDTO();
        $dto->name        = $city->name;
        $dto->id          = $this->faker->numberBetween(0, 100);
        $dto->regionId    = $city->region_id;
        $dto->typeId      = $city->type_id;
        $dto->code        = $city->code;
        $dto->longitude   = $city->longitude;
        $dto->latitude    = $city->latitude;
        $dto->coordinates = $city->coordinates;

        dispatch(new CreateCityCommand($dto));

        $this->assertDatabaseHas('cities', [
            'id'          => $dto->id,
            'name'        => $dto->name,
            'region_id'   => $dto->regionId,
            'type_id'     => $dto->typeId,
            'code'        => $dto->code,
            'longitude'   => $dto->longitude,
            'latitude'    => $dto->latitude,
            'coordinates' => $dto->coordinates,
        ]);
    }

    public function testUpdateCity()
    {
        /** @var City $city */
        $city = City::factory()->create();

        $dto              = new CityDTO();
        $dto->id          = $city->id;
        $dto->name        = $this->faker->unique()->domainWord;
        $dto->regionId    = $city->region_id;
        $dto->typeId      = $this->faker->numberBetween(0, 100);
        $dto->code        = $this->faker->word;
        $dto->longitude   = $this->faker->latitude;
        $dto->latitude    = $this->faker->latitude;
        $dto->coordinates = json_encode($this->faker->localCoordinates);

        dispatch(new UpdateCityCommand($dto));

        $this->assertDatabaseHas('cities', [
            'name'        => $dto->name,
            'region_id'   => $dto->regionId,
            'type_id'     => $dto->typeId,
            'code'        => $dto->code,
            'longitude'   => $dto->longitude,
            'latitude'    => $dto->latitude,
            'coordinates' => $dto->coordinates,
        ]);
    }
}
