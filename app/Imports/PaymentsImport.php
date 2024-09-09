<?php

declare(strict_types=1);

namespace App\Imports;

use App\Enums\Microsites\MicrositeCurrency;
use App\Models\Microsite;
use App\Models\Payment;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class PaymentsImport implements ToModel, WithHeadingRow, WithValidation, WithChunkReading, SkipsOnError, SkipsOnFailure
{
    use Importable;
    use SkipsErrors;
    use SkipsFailures;

    public $dataImport;

    public function __construct($dataImport)
    {
        $this->dataImport = $dataImport;
    }

    public function model(array $row)
    {
        $microsite = Microsite::firstWhere('slug', $row['microsite']);

        return new Payment([
            'microsite_id' => $microsite->id,
            'email' => $row['email'],
            'reference' => $microsite->slug . '-' . now()->format('YmdHis'),
            'description' => $row['description'],
            'amount' => $row['amount'],
            'currency' => $row['currency'],
        ]);
    }

    public function rules(): array
    {
        return [
            'microsite' => 'required|string',
            'email' => 'required|email',
            'description' => 'required|string|max:500',
            'amount' => 'required|numeric',
            'currency' => ['required', Rule::enum(MicrositeCurrency::class)],
        ];
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
