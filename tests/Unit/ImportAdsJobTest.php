<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Jobs\ImportAdsJob;
use App\Models\ImportJob;
use App\Services\ApiServiceInterface;
use Illuminate\Support\Facades\Log;
use Mockery;

class ImportAdsJobTest extends TestCase
{
    public function test_import_ads_job_process()
    {
        Log::shouldReceive('info')->atLeast()->once();
        Log::shouldReceive('error')->never();

        $importJob = new ImportJob();
        $importJob->status = 'pending';
        $importJob->save();
        
        $mockApiService = Mockery::mock(ApiServiceInterface::class);
        
        $mockApiService->shouldReceive('getOffers')
            ->once()
            ->andReturn(['data' => ['offers' => [1, 2]]]);
        
        $mockApiService->shouldReceive('getOfferDetails')
            ->twice()
            ->andReturnUsing(function ($offerId) {
                return [
                    'data' => [
                        'id' => $offerId,
                        'title' => "Produto $offerId",
                        'description' => "Descrição do Produto $offerId",
                        'status' => 'active',
                        'stock' => 10
                    ]
                ];
            });
        
        $mockApiService->shouldReceive('getOfferPrices')
            ->twice()
            ->andReturnUsing(function ($offerId) {
                return ['data' => ['price' => $offerId * 100]];
            });
        
        $mockApiService->shouldReceive('createHubOffer')->twice()->andReturn(true);
        
        $job = new ImportAdsJob($importJob, $mockApiService);
        $job->handle();
        
        $importJob->refresh();
        $this->assertEquals('completed', $importJob->status);
    }
}
