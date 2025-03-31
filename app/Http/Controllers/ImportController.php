<?php

namespace App\Http\Controllers;

use App\Jobs\ImportAdsJob;
use App\Models\ImportJob;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class ImportController extends Controller
{
    public function importAds(Request $request): JsonResponse
    {
        $importJob = ImportJob::create([
            'status' => 'pending',
        ]);

        ImportAdsJob::dispatch($importJob);

        return response()->json([
            'message' => 'Importação agendada com sucesso!',
            'importJobId' => $importJob->id,
        ]);
    }
}