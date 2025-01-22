<?php

declare(strict_types=1);

namespace Tests\Feature\Planning;

use App\Libraries\Codes\ResponseCodes;
use App\Module\Planning\Models\ContainerInvoice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

final class ContainerInvoiceTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function testContainerInvoiceDestroy()
    {
        /** @var ContainerInvoice $containerInvoice */
        $containerInvoice = ContainerInvoice::factory()->create();

        $response = $this->postJson(
            route('container.invoice.detach', [
                'containerId' => $containerInvoice->container_id,
                'invoiceIds'  => [$containerInvoice->invoice_id]
            ]),
        );

        $response->assertStatus(ResponseCodes::SUCCESS)
            ->assertJson([
                'message' => "Накладные из контейнера удалены!"
            ]);

        $this->assertSoftDeleted('containers_invoices', [
            'id' => $containerInvoice->id
        ]);
    }
}
