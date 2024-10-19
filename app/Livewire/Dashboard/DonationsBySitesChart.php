<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use Filament\Widgets\ChartWidget;

class DonationsBySitesChart extends ChartWidget
{
    protected static ?string $maxHeight = '300px';

    public array $sites;

    public function getHeading(): string
    {
        return __('Total donations by sites');
    }

    public function getDescription(): string
    {
        return __('Sum of donations by site');
    }

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
