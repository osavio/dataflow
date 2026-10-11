<?php

namespace App\Http\Services;

use App\Jobs\ProcessImport;
use App\Models\Import;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;

class ImportService
{
    public function __construct(private readonly Filesystem $storage) {}

    public function create(UploadedFile $file): Import
    {
        $path = null;
        $import = null;

        try {
            $path = $this->storage->putFile('imports', $file);
            
            $import = Import::create([
                'user_id' => 1, // Modificar para user real
                'file_path' => $path
            ]);
                
            ProcessImport::dispatch($import);
            
            return $import;
        } catch(Throwable $e) {
            if ($path) $this->storage->delete($path);

            Log::error('Failed to create import.', [
                'user_id' => 1, // Modificar para user real
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            throw new RuntimeException('It was not possible to create the import.', 0, $e);
        }
    }
}
