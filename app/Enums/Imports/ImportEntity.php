<?php

declare(strict_types=1);

namespace App\Enums\Imports;

use App\Imports\PaymentsImport;
use App\Models\Payment;
use Filament\Support\Contracts\HasLabel;

enum ImportEntity: string implements HasLabel
{
    case Payment = Payment::class;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Payment => 'Payment',
        };
    }

    public function getImportable(): string
    {
        return match ($this) {
            self::Payment => PaymentsImport::class,
        };
    }

    public function getTemplate(): string
    {
        return match ($this) {
            self::Payment => 'templates/import_payments_template.csv',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
