<?php

declare(strict_types=1);

namespace Tests\Feature\CourierApp;

use App\Libraries\Codes\ResponseCodes;
use App\Module\Courier\Models\Courier;
use App\Module\Delivery\Models\Delivery;
use App\Module\CourierApp\Models\CourierState;
use App\Module\Take\Models\OrderTake;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

final class CourierStateTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function testOrderTakeHereState()
    {
        /** @var CourierState $courierState */
        $courierState = CourierState::factory()->make();

        /** @var OrderTake $orderTake */
        $orderTake = OrderTake::factory()->create();

        /** @var Courier $courier */
        $courier = Courier::factory()->create();

        $data = [
            'clientId'  => $orderTake->id,
            'latitude'  => $courierState->latitude,
            'longitude' => $courierState->longitude,
        ];

        $this->actingAs($courier->user)
            ->postJson(route('courier-app.order-take.here-state'), $data)
            ->assertStatus(ResponseCodes::SUCCESS)
            ->assertJson([
                'message' => "Курьер успешно прибыл на забора!"
            ]);

        $this->assertDatabaseHas('courier_states', [
            'courier_id'  => $courier->id,
            'client_id'   => $data['clientId'],
            'client_type' => OrderTake::class,
            'latitude'    => $data['latitude'],
            'longitude'   => $data['longitude'],
        ]);
    }

    public function testDeliveryHereState()
    {
        /** @var CourierState $courierState */
        $courierState = CourierState::factory()->make();

        /** @var Delivery $delivery */
        $delivery = Delivery::factory()->create();

        /** @var Courier $courier */
        $courier = Courier::factory()->create();

        $data = [
            'clientId'  => $delivery->id,
            'latitude'  => $courierState->latitude,
            'longitude' => $courierState->longitude,
        ];

        $this->actingAs($courier->user)
            ->postJson(route('courier-app.delivery.here-state'), $data)
            ->assertStatus(ResponseCodes::SUCCESS)
            ->assertJson([
                'message' => "Курьер успешно прибыл на доставку!"
            ]);

        $this->assertDatabaseHas('courier_states', [
            'courier_id'  => $courier->id,
            'client_id'   => $data['clientId'],
            'client_type' => Delivery::class,
            'latitude'    => $data['latitude'],
            'longitude'   => $data['longitude'],
        ]);
    }
}
