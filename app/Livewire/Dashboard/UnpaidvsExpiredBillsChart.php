<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use Filament\Widgets\ChartWidget;

class UnpaidvsExpiredBillsChart extends ChartWidget
{
    protected static ?string $maxHeight = '300px';

    public $unpaidBills;
    public $expiredBills;

    public function getHeading(): string
    {
        return __('Unpaid vs Expired Bills');
    }

    public function getDescription(): string
    {
        return __('Amount of Unpaid and Expired amount payments type bill');
    }

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Blog posts created',
                    'data' => [$this->unpaidBills, $this->expiredBills],
                    'backgroundColor' => [
                        '#fbbf24',
                        '#ef4444',
                    ],
                    'hoverOffset' => 10,
                ],
            ],
            'labels' => [
                'Unpaid',
                'Expired',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
