<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use Filament\Widgets\ChartWidget;

class SubscriptionsOverTimeChart extends ChartWidget
{
    public array $monthData;

    public function getHeading(): string
    {
        return __('Subscriptions Over Time');
    }

    public function getDescription(): string
    {
        return __('Amount of subscriptions created this year');
    }

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
