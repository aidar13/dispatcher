<?php

declare(strict_types=1);

namespace App\Module\Planning\Models;

use App\Module\Order\Models\Invoice;
use Database\Factories\ContainerInvoiceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $container_id
 * @property int $invoice_id
 * @property int $position
 * @property int|null $status_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property-read Container $container
 * @property-read Invoice $invoice
 */
final class ContainerInvoice extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'containers_invoices';

    protected static function newFactory(): ContainerInvoiceFactory
    {
        return ContainerInvoiceFactory::new();
    }

    public function container(): BelongsTo
    {
        return $this->belongsTo(Container::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function setContainerId(int $containerId): void
    {
        $this->container_id = $containerId;
    }

    public function setInvoiceId(int $invoiceId): void
    {
        $this->invoice_id = $invoiceId;
    }

    public function setPosition(int $position): void
    {
        $this->position = $position;
    }
}
