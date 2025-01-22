<?php

namespace Database\Seeders;

use App\Module\DispatcherSector\Models\DispatcherSector;
use App\Module\DispatcherSector\Models\Wave;
use Illuminate\Database\Seeder;

class WaveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $dispatcherSectors = DispatcherSector::all();

        /** @var DispatcherSector $dispatcherSector */
        foreach ($dispatcherSectors as $dispatcherSector) {
            foreach ($this->getDefaultWaves() as $item) {
                $wave                       = new Wave();
                $wave->dispatcher_sector_id = $dispatcherSector->id;
                $wave->title                = $item['title'];
                $wave->from_time            = $item['fromTime'];
                $wave->to_time              = $item['toTime'];
                $wave->save();
            }
        }
    }

    private function getDefaultWaves(): array
    {
        return [
            ['title' => '1 Волна', 'fromTime' => '08:00', 'toTime' => '20:00'],
        ];
    }
}
