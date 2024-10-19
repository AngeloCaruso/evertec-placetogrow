<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use Filament\Widgets\ChartWidget;

class ActiveVsInactiveSubsChart extends ChartWidget
{
    protected static ?string $heading = 'Active vs Inactive Subscriptions';
    protected static ?string $description =  'Amount of Active and Inactive subscriptions';
    protected static ?string $maxHeight = '262px';

    public int $active;
    public int $inactive;

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Blog posts created',
                    'data' => [$this->active, $this->inactive],
                    'backgroundColor' => [
                        '#22c55e',
                        '#ef4444',
                    ],
                    'hoverOffset' => 10,
                ],
            ],
            'labels' => [
                'Active',
                'Inactive',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
