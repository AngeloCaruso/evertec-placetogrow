<?php

namespace App\Enums\Microsites;

use Filament\Support\Contracts\HasLabel;

enum MicrositeCurrency: string implements HasLabel
{
    case USD = 'USD';
    case ARS = 'ARS';
    case BRL = 'BRL';
    case CLP = 'CLP';
    case COP = 'COP';
    case MXN = 'MXN';
    case PEN = 'PEN';
    case UYU = 'UYU';
    case VEF = 'VEF';
    case EUR = 'EUR';
    case GBP = 'GBP';
    case AUD = 'AUD';
    case CAD = 'CAD';
    case JPY = 'JPY';
    case CNY = 'CNY';
    case KRW = 'KRW';
    case INR = 'INR';

    public function getLabel(): string
    {
        return match ($this) {
            self::USD => 'USD',
            self::ARS => 'ARS',
            self::BRL => 'BRL',
            self::CLP => 'CLP',
            self::COP => 'COP',
            self::MXN => 'MXN',
            self::PEN => 'PEN',
            self::UYU => 'UYU',
            self::VEF => 'VEF',
            self::EUR => 'EUR',
            self::GBP => 'GBP',
            self::AUD => 'AUD',
            self::CAD => 'CAD',
            self::JPY => 'JPY',
            self::CNY => 'CNY',
            self::KRW => 'KRW',
            self::INR => 'INR',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
