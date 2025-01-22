<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Module\Notification\Commands\SendTelegramMessageCommand;
use App\Module\Notification\Models\TelegramChat;
use App\Module\Order\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

final class OrderCoordinateReport extends Command
{
    protected $signature = 'order:coordiate-report
        {startDate? : Дата с в формате Y-m-d H:i}
        {endDate? : Дата до в формате Y-m-d H:i}';

    protected $description = 'Отчет по заказам без координат';

    public function handle(): void
    {
        $startDate = $this->argument('startDate') ?: now()->subHours(4)->format('Y-m-d H:i');
        $endDate   = $this->argument('endDate') ?: now()->addDay()->format('Y-m-d H:i');

        $object = Order::query()
            ->select(['id', 'sender_id', 'number'])
            ->whereHas('sender', function (Builder $builder) {
                $builder
                    ->whereNull('warehouse_id')
                    ->whereNull('latitude')
                    ->whereNull('longitude');
            })
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<', $endDate);

        $count = $object->count();

        $this->info("Найдено $count");
        $progressBar = $this->output->createProgressBar($count);

        if ($count < 1) {
            return;
        }

        dispatch(new SendTelegramMessageCommand(
            TelegramChat::ALERT_CHAT_ID,
            'Заказы без координат : ' . implode($object->get()->pluck('number')->toArray())
        ));

        $progressBar->finish();
    }
}
