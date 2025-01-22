<?php

declare(strict_types=1);

namespace App\Module\Planning\Repositories\Http;

use App\Helpers\DateHelper;
use App\Module\OneC\Contracts\Integration\HttpClientOneC;
use App\Module\OneC\Contracts\Integration\Integration1CConfigContract;
use App\Module\Order\Models\Invoice;
use App\Module\Planning\Contracts\Repositories\Integration\SendToAssemblyRepository;
use App\Module\Planning\DTO\OnecContainerCollectionDTO;
use App\Module\Planning\Models\Container;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

final class ContainerRepository implements SendToAssemblyRepository
{
    public function __construct(
        private readonly HttpClientOneC $clientOneC,
        private readonly Integration1CConfigContract $configService
    ) {
    }

    public function send(EloquentCollection $containers): Collection
    {
        $config = $this->configService->getMainConfig();
        $data   = [];

        /** @var Container $container */
        foreach ($containers as $container) {
            $waveDateFrom = DateHelper::getISOFormat(Carbon::make($container->date . $container->wave->from_time));
            $waveDateTo   = DateHelper::getISOFormat(Carbon::make($container->date . $container->wave->to_time));

            $invoices = $container->invoices->filter()
                ->transform(fn(Invoice $invoice) => [
                    'invoice_number' => $invoice->invoice_number,
                ])->values()
                ->toArray();

            $data[] = [
                'document_type'  => Container::ONE_C_DOCUMENT_TYPE_NAME,
                'container_id'   => $container->id,
                'container_name' => $container->title,
                'city_id'        => $container->sector->dispatcherSector->city_id,
                'doc_date'       => DateHelper::getISOFormat($container->created_at),
                'sector_id'      => (string)$container->sector_id,
                'wave_date_from' => $waveDateFrom,
                'wave_date_to'   => $waveDateTo,
                'courier_id'     => $container->courier_id,
                'invoices'       => $invoices,
            ];
        }

        $response = $this->clientOneC->makeRequest(
            $config,
            'POST',
            'integrationinternal/DocCreation',
            ['documents' => $data]
        );

        Log::info('Отправка контейнеров на сборку', [
            'status'   => $response->status(),
            'response' => $response->json(),
            'data'     => $data,
        ]);

        if ($error = $response->json('error')) {
            Log::info('Не удалось отправить контейнеров на сборку', [
                'status'   => $response->status(),
                'response' => $response->json(),
                'data'     => $data,
            ]);
            throw new \DomainException('Ошибка при отправке контейнеров в 1С: ' . $error);
        }

        return OnecContainerCollectionDTO::fromArray($response->json('documents'));
    }
}
