<?php

declare(strict_types=1);

namespace App\Enums\System;

enum IdTypes: string
{
    case CC = 'cc';
    case CE = 'ce';
    case TI = 'ti';
    case NIT = 'nit';
    case RUT = 'rut';

    public function getLabel(): string
    {
        return match ($this) {
            self::CC => 'Cédula de ciudadanía',
            self::CE => 'Cédula de extranjería',
            self::TI => 'Tarjeta de identidad',
            self::NIT => 'NIT',
            self::RUT => 'RUT',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
