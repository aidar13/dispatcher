<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Module\Courier\Models\Courier;
use App\Module\Delivery\Models\Delivery;
use App\Module\Notification\Contracts\Repositories\SendEmailNotificationRepository;
use App\Module\Notification\DTO\EmailNotificationDTO;
use App\Module\Status\Models\OrderStatus;
use App\Module\Status\Models\RefStatus;
use App\Module\Status\Models\StatusSource;
use Illuminate\Console\Command;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

final class CourierMpkReport extends Command
{
    protected $signature = 'courier:mpk-report
        {--mpk : получить курьеров которые закрывали через МПК}
        {--not-mpk : получить курьеров которые не закрывали доставки через МПК}
        {startDate? : Дата с в формате Y-m-d}
        {endDate? : Дата до в формате Y-m-d}';

    protected $description = 'Обновление диспетчер сектора и сектор в заказе';

    /**
     * @throws BindingResolutionException
     */
    public function handle(): void
    {
        $startDate = $this->argument('startDate') ?: now()->subHours(2)->format('Y-m-d');
        $endDate   = $this->argument('endDate') ?: now()->addDay()->format('Y-m-d');

        if ($this->option('not-mpk')) {
            $this->notMpkReport($startDate, $endDate);
        }

        if ($this->option('mpk')) {
            $this->mpkReport($startDate, $endDate);
        }
    }

    /**
     * @throws BindingResolutionException
     */
    private function notMpkReport(string $startDate, string $endDate): void
    {
        $couriers = $this->getCouriers($startDate, $endDate);

        $items = [];

        /** @var Courier $courier */
        foreach ($couriers as $courier) {
            if ($this->useNewMpk($courier)) {
                $items[] = $this->formatData($courier);
            }
        }

        $this->sendEmail($items, "Отчет по курьерам которые не закрывали доставки через МПК за период с $startDate до $endDate");
    }

    /**
     * @throws BindingResolutionException
     */
    private function mpkReport(string $startDate, string $endDate): void
    {
        $couriers = $this->getCouriers($startDate, $endDate);

        $items = [];

        /** @var Courier $courier */
        foreach ($couriers as $courier) {
            if (!$this->useNewMpk($courier)) {
                $items[] = $this->formatData($courier);
            }
        }

        $this->sendEmail($items, "Отчет по курьерам которые закрывали доставки через МПК за период с $startDate до $endDate");
    }

    private function useNewMpk(Courier $courier): bool
    {
        $found = false;

        /** @var Delivery $delivery */
        foreach ($courier->deliveries as $delivery) {
            /** @var OrderStatus $status */
            foreach ($delivery->statuses as $status) {
                if ($status->code === RefStatus::CODE_DELIVERED && $status->source_id !== StatusSource::ID_DISPATCHER) {
                    $found = true;
                }

                if ($status->code === RefStatus::CODE_DELIVERED && $status->source_id === StatusSource::ID_DISPATCHER) {
                    return false;
                }
            }
        }

        return $found;
    }

    /**
     * @throws BindingResolutionException
     */
    private function sendEmail(array $items, string $subject): void
    {
        $content = view('excel.mpk-courier', [
            'couriers' => $items,
            'subject'  => $subject
        ])->render();

        $dto = new EmailNotificationDTO();
        $dto->setEmails(['kanybek@spark.kz']);
        $dto->setSubject($subject);
        $dto->setContent($content);

        /** @var SendEmailNotificationRepository $repository */
        $repository = app()->make(SendEmailNotificationRepository::class);
        $repository->send($dto);
    }

    private function getCouriers(string $startDate, string $endDate): Collection
    {
        /** @var Collection $couriers */
        $couriers = Courier::query()
            ->with([
                'deliveries' => function ($query) use ($startDate, $endDate) {
                    return $query->select(['id', 'invoice_id', 'courier_id'])
                        ->with(['statuses' => fn($query) => $query->where('code', RefStatus::CODE_DELIVERED)])
                        ->whereDate('created_at', '>=', $startDate)
                        ->whereDate('created_at', '<', $endDate);
                }
            ])
            ->whereRelation('deliveries.statuses', function (Builder $builder) use ($startDate, $endDate) {
                return $builder->where('code', RefStatus::CODE_DELIVERED)
                    ->whereDate('created_at', '>=', $startDate)
                    ->whereDate('created_at', '<', $endDate);
            })
            ->get();

        $this->info("Найдено: " . $couriers->count());

        return $couriers;
    }

    private function formatData(Courier $courier): array
    {
        return [
            'id'          => $courier->id,
            'fullName'    => $courier->full_name,
            'statusTitle' => $courier->status?->title,
            'dsName'      => $courier->dispatcherSector?->name,
        ];
    }
}
