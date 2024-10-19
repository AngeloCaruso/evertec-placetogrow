<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use Filament\Widgets\ChartWidget;

class SubscriptionsOverTimeChart extends ChartWidget
{
    protected static ?string $heading = 'Subscriptions Over Time';
    protected static ?string $description =  'Amount of subscriptions created this year';

    public array $monthData;

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Subscriptions created',
                    'data' => array_values($this->monthData),
                ],
            ],
            'labels' => array_keys($this->monthData),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
