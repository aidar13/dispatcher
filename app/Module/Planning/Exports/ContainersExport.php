<?php

declare(strict_types=1);

namespace App\Module\Planning\Exports;

use Illuminate\Support\Collection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

final class ContainersExport implements FromView, ShouldAutoSize
{
    public function __construct(
        private readonly Collection $containers
    ) {
    }

    public function view(): View
    {
        set_time_limit(0);

        /** @var View $view */
        $view = view('excel.containers', [
            'containers' => $this->containers->sortByDesc('id'),
        ]);

        return $view;
    }
}
