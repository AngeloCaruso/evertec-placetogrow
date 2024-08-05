<?php

declare(strict_types=1);

namespace App\Enums\System;

enum IdTypes: string
{
    case CC = 'cc';
    case CE = 'ce';
    case NIT = 'nit';
    case PP = 'pp';
    case RC = 'rc';
    case RUT = 'rut';
    case DNI = 'dni';

    public function getLabel(): string
    {
        return match ($this) {
            self::CC => 'Cédula de ciudadanía',
            self::CE => 'Cédula de extranjería',
            self::NIT => 'NIT',
            self::PP => 'Pasaporte',
            self::RC => 'Registro civil',
            self::RUT => 'RUT',
            self::DNI => 'DNI',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
