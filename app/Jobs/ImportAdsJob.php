<?php

namespace App\Jobs;

use App\Models\Ads;
use App\Models\ImportJob;
use App\States\ProcessingState;
use App\States\CompletedState;
use App\States\PendingState;
use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

Const APIURL = 'http://mockoon:3000';

class ImportAdsJob implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    protected $importJob;

    public function __construct(ImportJob $importJob)
    {
        $this->importJob = $importJob;
    }

    public function handle()
    {
        \Log::info('Estado atualizado para processing.');

        $this->importJob->setState('processing');
        
        try {

            $response = Http::get(APIURL.'/offers', [
                'page' => 1,
            ]);
    
            $offers = $response->json();

            foreach ($offers['data']['offers'] as $offerId) {
                $detailedResponse = Http::get(APIURL."/offers/{$offerId}");
                $price = Http::get(APIURL."/offers/{$offerId}/prices");

                if ($detailedResponse->successful()) {
                $detailedOffer = $detailedResponse->json();
                $priceData = $price->json();

                \Log::info('Enviando oferta para o HUB:');
                Http::post(APIURL.'/hub/create-offer', [
                    'title' => $detailedOffer['data']['title'],
                    'description' => $detailedOffer['data']['description'],
                    'status' => $detailedOffer['data']['status'],
                    'stock' => $detailedOffer['data']['stock'],
                ]);

                Ads::updateOrCreate(
                    ['marketplace_id' => $detailedOffer['data']['id']], // Garantindo que não haja duplicidade
                    [
                    'title' => $detailedOffer['data']['title'],
                    'description' => $detailedOffer['data']['description'],
                    'price' => $priceData['data']['price'],
                    'updated_at' => now(),
                    ]
                );
                }
            }

            $this->importJob->setState('completed');

        } catch (\Exception $e) {
            \Log::error('Erro durante a importação: ' . $e->getMessage());
            $this->importJob->setState('failed');
        }
    }
}
