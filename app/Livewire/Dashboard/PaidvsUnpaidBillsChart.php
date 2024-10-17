<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use Filament\Widgets\ChartWidget;

class PaidvsUnpaidBillsChart extends ChartWidget
{
    protected static ?string $heading = 'Paid vs Unpaid Bills';
    protected static ?string $description =  'Amount of Paid and Unpaid payments type bill';
    protected static ?string $maxHeight = '300px';
    public $paidBills;
    public $unpaidBills;

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Blog posts created',
                    'data' => [$this->paidBills, $this->unpaidBills],
                    'backgroundColor' => [
                        '#22c55e',
                        '#fbbf24',
                    ],
                    'hoverOffset' => 10,
                ],
            ],
            'labels' => [
                'Paid',
                'Unpaid',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
