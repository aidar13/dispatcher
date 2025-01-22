<?php

declare(strict_types=1);

namespace Tests\Feature\Order;

use App\Libraries\Codes\ResponseCodes;
use App\Module\Order\Models\FastDeliveryOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Tests\TestCase;

final class FastDeliveryOrderTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        Carbon::setTestNow(now());
    }

    public function testSetFastDeliveryCourier()
    {
        /** @var FastDeliveryOrder $order */
        $order = FastDeliveryOrder::factory()->create([
            'courier_name' => null
        ]);

        /** @var FastDeliveryOrder $model */
        $model = FastDeliveryOrder::factory()->make();

        $response = $this->putJson(route('fast-delivery-order.set-courier', $order->internal_id), [
            'courierName'    => $model->courier_name,
            'courierPhone'   => $model->courier_phone,
            'trackLink'      => $model->tracking_url,
            'internalStatus' => $model->internal_status,
            'price'          => $model->price,
        ]);

        $response->assertStatus(ResponseCodes::SUCCESS)
            ->assertJson([
                'message' => "Успешно назначен курьер!"
            ]);

        $this->assertDatabaseHas('fast_delivery_orders', [
            'internal_id'     => $order->internal_id,
            'courier_name'    => $model->courier_name,
            'courier_phone'   => $model->courier_phone,
            'tracking_url'    => $model->tracking_url,
            'internal_status' => $model->internal_status,
            'price'           => $model->price,
        ]);
    }
}
