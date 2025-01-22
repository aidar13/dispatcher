<?php

namespace Database\Factories;

use App\Module\Delivery\Models\Delivery;
use App\Module\Delivery\Models\RouteSheet;
use App\Module\Delivery\Models\RouteSheetInvoice;
use App\Module\Order\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Delivery>
 */
class RouteSheetInvoiceFactory extends Factory
{
    protected $model = RouteSheetInvoice::class;

    public function definition(): array
    {
        return [
            'route_sheet_id' => RouteSheet::factory()->create(),
            'invoice_id'     => Invoice::factory()->create(),
        ];
    }
}
