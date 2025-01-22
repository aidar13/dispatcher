<?php

declare(strict_types=1);

namespace Tests\Feature\Country;

use App\Module\City\Commands\CreateCountryCommand;
use App\Module\City\DTO\CountryDTO;
use App\Module\City\Models\Country;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

final class CountryTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testCreateCountry()
    {
        /** @var Country $country */
        $country = Country::factory()->make();

        $dto        = new CountryDTO();
        $dto->name  = $country->name;
        $dto->id    = $this->faker->numberBetween(0, 100);
        $dto->title = $country->title;

        dispatch(new CreateCountryCommand($dto));

        $this->assertDatabaseHas('countries', [
            'id'    => $dto->id,
            'name'  => $dto->name,
            'title' => $dto->title
        ]);
    }
}
