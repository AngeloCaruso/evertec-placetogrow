<?php

namespace App\Imports;

use App\Enums\Microsites\MicrositeCurrency;
use App\Models\Microsite;
use App\Models\Payment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
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
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\ImportFailed;

class PaymentsImport implements ToModel, WithHeadingRow, WithValidation, WithChunkReading, SkipsOnError, SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;

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
            'expires_at' => $row['expires_at'],
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
            'expires_at' => 'required|date_format:d/m/Y H:i:s',
        ];
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function registerEvents(): array
    {
        return [
            ImportFailed::class => function (ImportFailed $event) {
                Log::error($event->getException());
            },
            AfterImport::class => function (AfterImport $event) {
                Log::info('Payments imported successfully');
            },
        ];
    }
}
