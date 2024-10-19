<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;

class ActiveVsInactiveSubsChart extends ChartWidget
{
    protected static ?string $maxHeight = '262px';

    public int $active;
    public int $inactive;

    public function getHeading(): string
    {
        return __('Active vs Inactive Subscriptions');
    }

    public function getDescription(): string
    {
        return __('Amount of Active and Inactive subscriptions');
    }

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
