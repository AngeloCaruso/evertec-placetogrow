<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BillStatsOverview extends BaseWidget
{
    public $totalBills;
    public $paidBills;
    public $rejectedBills;

    protected function getStats(): array
    {
        return [
            Stat::make(__('Total amount of bills'), $this->totalBills),
            Stat::make(__('Percentage of paid bills'), number_format($this->paidBills / $this->totalBills * 100, 2) . '%'),
            Stat::make(__('Rejected payments'), $this->rejectedBills),
        ];
    }
}
