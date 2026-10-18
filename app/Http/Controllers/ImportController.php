<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreImportRequest;
use App\Services\ImportService;
use App\Http\Resources\ImportResource;
use App\Models\Import;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function __construct(
        private ImportService $importService
    ){}

    public function store(StoreImportRequest $request): ImportResource
    {
        $import = $this->importService->create($request->user(), $request->file('file'));
        
        return new ImportResource($import);
    }

    public function show(Import $import): ImportResource
    {
        $this->authorize('getImport', $import);

        $import = $this->importService->getImport($import);
        
        return new ImportResource($import);
    }
}
