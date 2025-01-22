<?php

namespace App\Module\Courier\Exports;

use App\Module\Courier\Contracts\Queries\CourierQuery;
use App\Module\Courier\DTO\CourierExportDTO;
use App\Module\Courier\Models\Courier;
use Illuminate\Contracts\Container\BindingResolutionException;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

readonly class CouriersExport implements FromArray, WithStrictNullComparison, WithHeadings
{
    private CourierQuery $query;

    /**
     * @throws BindingResolutionException
     */
    public function __construct(
        private CourierExportDTO $DTO,
    ) {
        $this->query = app()->make(CourierQuery::class);
    }

    public function array(): array
    {
        $couriers = $this->query->getAllForExport($this->DTO);
        $array    = [];

        /** @var Courier $courier */
        foreach ($couriers as $courier) {
            $takenInfosInvoiceCargos = $courier->getTakenInfosInvoiceCargos();
            $deliveryInvoiceCargos   = $courier->getDeliveryInvoiceCargos();

            $takenInfosPlaces        = $takenInfosInvoiceCargos->sum('places');
            $takenInfosInvoicesCount = $takenInfosInvoiceCargos->count();
            $takenInfosWeight        = $takenInfosInvoiceCargos->sum('weight');
            $takenInfosVolume        = $takenInfosInvoiceCargos->sum('volume');
            $takenInfosVolumeWeight  = $takenInfosInvoiceCargos->sum('volume_weight');

            $deliveredPlaces        = $deliveryInvoiceCargos->sum('places');
            $deliveredInvoicesCount = $deliveryInvoiceCargos->count();
            $deliveredWeight        = $deliveryInvoiceCargos->sum('weight');
            $deliveredVolume        = $deliveryInvoiceCargos->sum('volume');
            $deliveredVolumeWeight  = $deliveryInvoiceCargos->sum('volume_weight');

            $array[] = [
                'id'                      => $courier->id,
                'fullName'                => $courier->full_name,
                'createdAt'               => $courier->created_at,
                'dispatcherSector'        => $courier->dispatcherSector?->name,
                'takenInfosPlaces'        => $takenInfosPlaces,
                'takenInfosInvoicesCount' => $takenInfosInvoicesCount,
                'takenInfosWeight'        => $takenInfosWeight,
                'takenInfosVolume'        => $takenInfosVolume,
                'deliveredPlaces'         => $deliveredPlaces,
                'deliveredInvoicesCount'  => $deliveredInvoicesCount,
                'deliveredWeight'         => $deliveredWeight,
                'deliveredVolume'         => $deliveredVolume,
                'vehicleVolume'           => $courier->car?->carType->volume,
                'vehicleCapacity'         => $courier->car?->carType->capacity,
                'stopsCount'              => $courier->stops_count,
                'allPlaces'               => $takenInfosPlaces + $deliveredPlaces,
                'allInvoices_count'       => $takenInfosInvoicesCount + $deliveredInvoicesCount,
                'allWeight'               => $takenInfosWeight + $deliveredWeight,
                'allVolume'               => $takenInfosVolume + $deliveredVolume,
                'allVolumeWeight'         => $takenInfosVolumeWeight + $deliveredVolumeWeight,
            ];
        }
        return $array;
    }

    public function headings(): array
    {
        return [
            'ID курьера',
            'ФИО курьера',
            'Дата создания',
            'Сектор диспетчера',
            'Кол-во забранных мест',
            'кол-во забранных накладных',
            'Общий вес забранных накладных',
            'Общий объём забранных накладных',
            'Кол-во доставленных мест',
            'кол-во доставленных накладных',
            'Общий вес доставленных накладных',
            'Общий объём доставленных накладных',
            'Объём авто',
            'Грузоподъёмность авто.',
            'Итого стопов',
            'Итого кол-во мест',
            'Итого кол-во накладных',
            'Итого общий вес',
            'Итого общий объём',
            'Итого общий объёмный вес в кг',
        ];
    }
}
