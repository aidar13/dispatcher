<?php

declare(strict_types=1);

namespace Tests\Feature\Order;

use App\Module\Order\Commands\UpdateSlaCommand;
use App\Module\Order\DTO\UpdateSlaDTO;
use App\Module\Order\Models\Sla;
use App\Module\Order\Commands\CreateSlaCommand;
use App\Module\Order\DTO\CreateSlaDTO;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

final class SlaTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testCreateSla()
    {
        /** @var Sla $sla */
        $sla = Sla::factory()->make();

        $dto                 = new CreateSlaDTO();
        $dto->cityFrom       = $sla->city_from;
        $dto->cityTo         = $sla->city_to;
        $dto->pickup         = $sla->pickup;
        $dto->id             = $this->faker->numberBetween(0, 100);
        $dto->processing     = $sla->processing;
        $dto->transit        = $sla->transit;
        $dto->delivery       = $sla->delivery;
        $dto->shipmentTypeId = $sla->shipment_type_id;

        dispatch(new CreateSlaCommand($dto));

        $this->assertDatabaseHas('sla_sla', [
            'id'               => $dto->id,
            'city_from'        => $dto->cityFrom,
            'city_to'          => $dto->cityTo,
            'pickup'           => $dto->pickup,
            'processing'       => $dto->processing,
            'transit'          => $dto->transit,
            'delivery'         => $dto->delivery,
            'shipment_type_id' => $dto->shipmentTypeId,
        ]);
    }

    public function testUpdateSla()
    {
        /** @var Sla $sla */
        $sla = Sla::factory()->create();

        $dto                 = new UpdateSlaDTO();
        $dto->id             = $sla->id;
        $dto->cityFrom       = $this->faker->randomNumber();
        $dto->cityTo         = $this->faker->randomNumber();
        $dto->pickup         = $this->faker->randomNumber();
        $dto->processing     = $this->faker->randomNumber();
        $dto->transit        = $this->faker->randomNumber();
        $dto->delivery       = $this->faker->randomNumber();
        $dto->shipmentTypeId = $this->faker->numberBetween(1, 2);

        dispatch(new UpdateSlaCommand($dto));

        $this->assertDatabaseHas('sla_sla', [
            'id'               => $dto->id,
            'city_from'        => $dto->cityFrom,
            'city_to'          => $dto->cityTo,
            'pickup'           => $dto->pickup,
            'processing'       => $dto->processing,
            'transit'          => $dto->transit,
            'delivery'         => $dto->delivery,
            'shipment_type_id' => $dto->shipmentTypeId,
        ]);
    }
}
