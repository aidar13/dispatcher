<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Module\Order\Commands\SetReceiverDispatcherSectorCommand;
use App\Module\Order\Commands\SetSenderDispatcherSectorCommand;
use App\Module\Order\Events\InvoiceSectorsUpdatedEvent;
use App\Module\Order\Models\Invoice;
use Illuminate\Bus\Dispatcher;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

final class UpdateInvoiceSectors extends Command
{
    protected $signature = 'invoice:update-sector
        {startDate? : Дата с в формате Y-m-d}
        {endDate? : Дата до в формате Y-m-d}';

    protected $description = 'Обновление диспетчер сектора и сектор в заказе';

    public function handle(): void
    {
        /** @var Dispatcher $dispatcher */
        $dispatcher = app(Dispatcher::class);

        $startDate = $this->argument('startDate') ?: now()->subHours(2)->format('Y-m-d H:i:s');
        $endDate   = $this->argument('endDate') ?: now()->addDay()->format('Y-m-d');

        $object = Invoice::query()
            ->where(function (Builder $query) {
                $query
                    ->whereRelation('receiver', function (Builder $query) {
                        $query
                            ->whereNull('dispatcher_sector_id')
                            ->orWhereNull('sector_id');
                    })
                    ->orWhereRelation('order.sender', function (Builder $query) {
                        $query
                            ->whereNull('dispatcher_sector_id')
                            ->orWhereNull('sector_id');
                    });
            })
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<', $endDate);

        $count = $object->count();

        $this->info("Найдено $count");
        $progressBar = $this->output->createProgressBar($count);

        $object->chunk(100, function (Collection $collection) use ($progressBar, $dispatcher) {
            $collection->each(function (Invoice $invoice) use ($progressBar, $dispatcher) {
                try {
                    $dispatcher->dispatchNow(new SetSenderDispatcherSectorCommand($invoice->order->sender_id));
                    $dispatcher->dispatchNow(new SetReceiverDispatcherSectorCommand($invoice->receiver_id));

                    event(new InvoiceSectorsUpdatedEvent($invoice->id));

                    $progressBar->advance();
                } catch (\Throwable $exception) {
                    Log::info("Ошибка при обновлений диспетчер сектора накладной id = $invoice->id " . $exception->getMessage());
                    $this->error("Ошибка при обновлений диспетчер сектора накладной id = $invoice->id " . $exception->getMessage());
                }
            });
        });

        $progressBar->finish();
    }
}
