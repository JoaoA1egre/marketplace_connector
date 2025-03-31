<?php
namespace App\Http\Controllers;

use App\Jobs\ImportAdsJob;
use App\Models\ImportJob;
use App\Services\ApiServiceInterface;  // Adicionando o serviço
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class ImportController extends Controller
{
    protected $apiService;

    public function __construct(ApiServiceInterface $apiService)
    {
        $this->apiService = $apiService;
    }

    public function importAds(Request $request): JsonResponse
    {
        $importJob = ImportJob::create([
            'status' => 'pending',
        ]);

        ImportAdsJob::dispatch($importJob, $this->apiService);

        return response()->json([
            'success' => true,
            'data' => [
                'message' => 'Importação agendada com sucesso!',
                'importJobId' => $importJob->id,
            ],
        ], 201);
    }

    public function getImportJobStatus($id): JsonResponse
    {
        $importJob = ImportJob::find($id);

        if (!$importJob) {
            return response()->json([
                'success' => false,
                'error' => 'ImportJob não encontrado.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'status' => $importJob->status,
            ],
        ]);
    }
}