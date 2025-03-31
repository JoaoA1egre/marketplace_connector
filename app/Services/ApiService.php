<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ApiService implements ApiServiceInterface
{
    const APIURL = 'http://mockoon:3000';

    public function getOffers(int $page): array
{
    $offers = [];
    $currentPage = $page;

    do {
        $response = Http::get(self::APIURL . '/offers', ['page' => $currentPage]);
        $responseData = $response->json();
        
        $currentOffers = $responseData['data']['offers'] ?? [];
        
        if (!empty($currentOffers)) {
            $offers = array_merge($offers, $currentOffers);
        } else {
            break;
        }
        
        $currentPage = $responseData['pagination']['next_page'] ?? null;
    } while ($currentPage);

    return $offers;
}

    public function getOfferDetails(string $offerId): array
    {
        $response = Http::get(self::APIURL . "/offers/{$offerId}");
        return $response->json();
    }

    public function getOfferPrices(string $offerId): array
    {
        $response = Http::get(self::APIURL . "/offers/{$offerId}/prices");
        return $response->json();
    }

    public function createHubOffer(array $offerData): void
    {
        Http::post(self::APIURL . '/hub/create-offer', $offerData);
    }
}
