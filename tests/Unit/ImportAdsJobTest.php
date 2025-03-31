<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Jobs\ImportAdsJob;
use App\Models\ImportJob;
use Illuminate\Support\Facades\Http;

class ImportAdsJobTest extends TestCase
{
    public function test_import_ads_job_process()
    {

        $importJob = ImportJob::create([
            'status' => 'pending',
        ]);

        Http::fake([
            'http://mockoon:3000/offers' => Http::response([
                'data' => ['offers' => [1, 2]]
            ], 200),
            'http://mockoon:3000/offers/1' => Http::response([
                'data' => [
                    'id' => 1,
                    'title' => 'Produto 1',
                    'description' => 'Descrição do Produto 1',
                    'status' => 'active',
                    'stock' => 10
                ]
            ], 200),
            'http://mockoon:3000/offers/2' => Http::response([
                'data' => [
                    'id' => 2,
                    'title' => 'Produto 2',
                    'description' => 'Descrição do Produto 2',
                    'status' => 'inactive',
                    'stock' => 5
                ]
            ], 200),
            'http://mockoon:3000/offers/1/prices' => Http::response([
                'data' => ['price' => 100]
            ], 200),
            'http://mockoon:3000/offers/2/prices' => Http::response([
                'data' => ['price' => 200]
            ], 200),
            'http://mockoon:3000/hub/create-offer' => Http::response([], 201),
        ]);

        $job = new ImportAdsJob($importJob);
        $job->handle();

        $importJob->refresh();
        $this->assertEquals('completed', $importJob->status);

    }
}
