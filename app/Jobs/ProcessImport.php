<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Enums\ImportStatus;
use App\Models\Import;
use Throwable;

class ProcessImport implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;        // até 3 tentativas
    public int $timeout = 300;    // mata o Job se passar de 5 minutos

    /**
     * Create a new job instance.
     */
    public function __construct(public Import $import) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
    }

    public function failed(?Throwable $exception): void
    {
        $this->import->update([
            'status'        => ImportStatus::Failed,
            'error_message' => $exception?->getMessage(),
        ]);
    }
}
