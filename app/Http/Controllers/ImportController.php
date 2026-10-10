<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportStoreRequest;
use App\Http\Services\ImportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function __construct(
        private ImportService $importService
    ){}

    public function store(ImportStoreRequest $request): JsonResponse
    {
        $import = $this->importService->create($request->validated());
        
        return response()->json(
            $import,
            202
        );
    }
}
