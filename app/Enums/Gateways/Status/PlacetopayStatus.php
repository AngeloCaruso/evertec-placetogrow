<?php

namespace App\Enums\Gateways\Status;

enum PlacetopayStatus: string
{
    case Pending = 'PENDING';
    case Approved = 'APPROVED';
    case Rejected = 'REJECTED';
    case Parcial = 'APPROVED_PARTIAL';
    case Expired = 'PARTIAL_EXPIRED';

    public function getLabel(): string
    {
        /**
         * @TODO translate values
         */
        return match ($this) {
            self::Pending => 'Pendiente',
            self::Approved => 'Aprobado',
            self::Rejected => 'Rechazado',
            self::Parcial => 'Aprobado Parcial',
            self::Expired => 'Parcial Expirado',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
