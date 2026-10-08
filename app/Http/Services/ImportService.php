<?php

namespace App\Http\Services;

use App\Enums\ImportStatus;
use App\Models\Import;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

class ImportService
{
    public function create(array $data): Import
    {
        $path = null;

        try {
            $path = Storage::disk('s3')->putFile(
                'imports',
                $data['file']
            );
            
            $import = Import::create([
                'user_id' => 1, // Modificar para user real
                'file_path' => $path,
                'status' => ImportStatus::Pending,
                'error_message' => null
            ]);
                
            $this->publishImport($import);
            
            return $import;
        } catch(Throwable $e) {
            if ($path) Storage::disk('s3')->delete($path);

            Log::error('Failed to create import.', [
                'user_id' => 1, // Modificar para user real
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            throw new RuntimeException('It was not possible to create the import.', 0, $e);
        }
    }

    public function publishImport(Import $import): void 
    {
        // Publicar no RabbitMQ
    }
}
