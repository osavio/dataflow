<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreImportRequest;
use App\Http\Services\ImportService;
use App\Http\Resources\ImportResource;

class ImportController extends Controller
{
    public function __construct(
        private ImportService $importService
    ){}

    public function store(StoreImportRequest $request): ImportResource
    {
        $import = $this->importService->create($request->file('file'));
        
        return new ImportResource($import);
    }
}
