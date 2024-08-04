<?php

namespace App\Enums\Gateways\Status;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum PlacetopayStatus: string implements HasLabel, HasColor, HasIcon
{
    case Pending = 'PENDING';
    case Approved = 'APPROVED';
    case Rejected = 'REJECTED';
    case Parcial = 'APPROVED_PARTIAL';
    case Expired = 'EXPIRED';

    public function getLabel(): string
    {
        /**
         * @TODO translate values
         */
        return match ($this) {
            self::Pending => 'Pending',
            self::Approved => 'Approved',
            self::Rejected => 'Rejected',
            self::Parcial => 'Parcially Approved',
            self::Expired => 'Expired',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Approved => 'success',
            self::Rejected => 'danger',
            self::Parcial => 'warning',
            self::Expired => 'info',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Pending => 'heroicon-s-clock',
            self::Approved => 'heroicon-s-check-circle',
            self::Rejected => 'heroicon-s-x-circle',
            self::Parcial => 'heroicon-s-minus-circle',
            self::Expired => 'heroicon-s-clock',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
