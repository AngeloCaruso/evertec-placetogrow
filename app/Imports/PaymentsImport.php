<?php

declare(strict_types=1);

namespace App\Imports;

use App\Enums\Microsites\MicrositeCurrency;
use App\Models\Microsite;
use App\Models\Payment;
use Carbon\Carbon;
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
use Illuminate\Support\Str;

class PaymentsImport implements ToModel, WithHeadingRow, WithValidation, WithChunkReading, SkipsOnError, SkipsOnFailure
{
    use Importable;
    use SkipsErrors;
    use SkipsFailures;

    public function model(array $row)
    {
        $microsite = Microsite::firstWhere('slug', $row['microsite']);

        return new Payment([
            'microsite_id' => $microsite->id,
            'email' => $row['email'],
            'reference' => "PAY-" . Str::random(16),
            'description' => $row['description'],
            'amount' => $row['amount'],
            'currency' => $row['currency'],
            'limit_date' => Carbon::createFromFormat('d/m/Y', $row['limit_date'])->format('Y-m-d'),
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
            'limit_date' => 'required|date_format:d/m/Y',
        ];
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
