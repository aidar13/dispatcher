<?php

declare(strict_types=1);

namespace App\Module\Planning\Listeners;

use App\Module\Planning\Commands\UpdateContainerNumberCommand;
use App\Module\Planning\Commands\UpdateContainerStatusCommand;
use App\Module\Planning\Contracts\Queries\ContainerQuery;
use App\Module\Planning\DTO\OnecContainerDTO;
use App\Module\Planning\Events\ContainersSentToAssemblyEvent;
use App\Module\Planning\Models\Container;
use App\Module\Planning\Models\ContainerStatus;

final class ContainersSentToAssemblyListener
{
    public function __construct(private readonly ContainerQuery $query)
    {
    }

    public function handle(ContainersSentToAssemblyEvent $event): void
    {
        $containerIds = $this->getContainerIds($event);
        $containers   = $this->query->getAllByIds($containerIds, ['*'], ['fastDeliveryOrder']);

        /** @var Container $container */
        foreach ($containers as $container) {
            /** @var OnecContainerDTO $oneCContainer */
            $oneCContainer = $event->oneCContainers
                ->where('containerId', $container->id)
                ->first();

            dispatch(new UpdateContainerNumberCommand(
                $container->id,
                $oneCContainer->docNumber
            ));

            dispatch(new UpdateContainerStatusCommand(
                $container->id,
                ContainerStatus::ID_SEND_TO_ASSEMBLY
            ));
        }
    }

    private function getContainerIds(ContainersSentToAssemblyEvent $event): array
    {
        $sentContainerIds = $event->oneCContainers
            ->where('success', true)
            ->pluck('containerId')
            ->toArray();

        return array_intersect($event->containerIds, $sentContainerIds);
    }
}
