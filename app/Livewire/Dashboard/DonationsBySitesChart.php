<?php

namespace App\Livewire\Dashboard;

use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;

class DonationsBySitesChart extends ChartWidget
{
    protected static ?string $heading = 'Total donations by sites';
    protected static ?string $description =  'Sum of donations by site';
    protected static ?string $maxHeight = '300px';

    public array $sites;

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => array_keys($this->sites),
                    'data' => array_values($this->sites),
                ],
            ],
            'labels' => array_keys($this->sites),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
