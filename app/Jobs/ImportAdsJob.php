<?php
namespace App\Jobs;

use App\Models\Ads;
use App\Models\ImportJob;
use App\Services\ApiServiceInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportAdsJob implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    protected $importJob;
    protected $apiService;

    public function __construct(ImportJob $importJob, ApiServiceInterface $apiService)
    {
        $this->importJob = $importJob;
        $this->apiService = $apiService;
    }

    public function handle()
    {
        \Log::info('Estado atualizado para processing.');

        $this->importJob->setState('processing');

        try {
            $offers = $this->apiService->getOffers(1);

            foreach ($offers['data']['offers'] as $offerId) {
                $detailedOffer = $this->apiService->getOfferDetails($offerId);
                $priceData = $this->apiService->getOfferPrices($offerId);

                if ($detailedOffer) {
                    \Log::info('Enviando oferta para o HUB:');
                    $this->apiService->createHubOffer([
                        'title' => $detailedOffer['data']['title'],
                        'description' => $detailedOffer['data']['description'],
                        'status' => $detailedOffer['data']['status'],
                        'stock' => $detailedOffer['data']['stock'],
                    ]);

                    Ads::updateOrCreate(
                        ['marketplace_id' => $detailedOffer['data']['id']],
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
