<?php

declare(strict_types=1);

namespace App\Module\Delivery\Models;

use App\Module\Order\Models\Invoice;
use Database\Factories\RouteSheetInvoiceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Module\Delivery\Models\RouteSheetInvoice
 *
 * @property int $id
 * @property int $route_sheet_id
 * @property int $invoice_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read RouteSheet $routeSheet
 * @property-read Invoice $invoice
 * @method static RouteSheetInvoiceFactory factory($count = null, $state = [])
 */
final class RouteSheetInvoice extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected static function newFactory(): RouteSheetInvoiceFactory
    {
        return RouteSheetInvoiceFactory::new();
    }

    public function routeSheet(): BelongsTo
    {
        return $this->belongsTo(RouteSheet::class, 'route_sheet_id');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'id');
    }
}
