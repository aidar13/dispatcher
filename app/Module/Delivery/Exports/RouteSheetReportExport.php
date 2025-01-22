<?php

declare(strict_types=1);

namespace App\Module\Delivery\Exports;

use App\Module\Delivery\Models\RouteSheet;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

final class RouteSheetReportExport implements FromView, ShouldAutoSize
{
    public function __construct(
        private readonly RouteSheet $routeSheet
    ) {
    }

    public function view(): View
    {
        set_time_limit(0);

        /** @var View $view */
        $view = view('excel.route-sheet', [
            'routeSheet' => $this->routeSheet
        ]);

        return $view;
    }
}
