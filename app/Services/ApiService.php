<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ApiService implements ApiServiceInterface
{
    const APIURL = 'http://mockoon:3000';

    public function getOffers(int $page): array
    {
        $response = Http::get(self::APIURL . '/offers', ['page' => $page]);
        return $response->json();
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
