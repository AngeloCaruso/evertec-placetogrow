<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\Imports\ImportStatus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessDataImport implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;


    public function __construct(
        public $excelImport,
        public $dataImport,
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $this->excelImport->import($this->dataImport->file);

            $errors = [];

            foreach ($this->excelImport->failures() as $failure) {
                $message = implode('|', $failure->errors());
                $errors[] = "Error on line {$failure->row()}. {$message}";
            }

            foreach ($this->excelImport->errors() as $error) {
                $errors[] = $error;
            }

            $this->dataImport->errors = $errors;
            $this->dataImport->status = ImportStatus::Completed;
            $this->dataImport->update();
        } catch (\Exception $e) {
            $this->dataImport->status = ImportStatus::Failed;
            $this->dataImport->errors = [$e->getMessage()];
            $this->dataImport->update();
        }
    }
}
