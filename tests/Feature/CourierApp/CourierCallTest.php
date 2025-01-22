<?php

declare(strict_types=1);

namespace Tests\Feature\CourierApp;

use App\Libraries\Codes\ResponseCodes;
use App\Module\Courier\Models\Courier;
use App\Module\CourierApp\Models\CourierCall;
use App\Module\Delivery\Models\Delivery;
use App\Module\Take\Models\OrderTake;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

final class CourierCallTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function testOrderTakeCourierCall()
    {
        /** @var Courier $courier */
        $courier = Courier::factory()->create();

        /** @var CourierCall $courierCall */
        $courierCall = CourierCall::factory()->make();

        $data = [
            'clientId' => $courierCall->client_id,
            'phone'    => $courierCall->phone,
        ];

        $this->actingAs($courier->user)
            ->postJson(route('courier-app.order-take.courier-call'), $data)
            ->assertStatus(ResponseCodes::SUCCESS)
            ->assertJson([
                'message' => "Успешный ответ"
            ]);

        $this->assertDatabaseHas('courier_calls', [
            'courier_id'  => $courier->id,
            'client_id'   => $data['clientId'],
            'client_type' => OrderTake::class,
        ]);
    }

    public function testDeliveryCourierCall()
    {
        /** @var Courier $courier */
        $courier = Courier::factory()->create();

        /** @var CourierCall $courierCall */
        $courierCall = CourierCall::factory()->make();

        $data = [
            'clientId' => $courierCall->client_id,
            'phone'    => $courierCall->phone,
        ];

        $this->actingAs($courier->user)
            ->postJson(route('courier-app.delivery.courier-call'), $data)
            ->assertStatus(ResponseCodes::SUCCESS)
            ->assertJson([
                'message' => "Успешный ответ"
            ]);

        $this->assertDatabaseHas('courier_calls', [
            'courier_id'  => $courier->id,
            'client_id'   => $data['clientId'],
            'client_type' => Delivery::class,
        ]);
    }
}
