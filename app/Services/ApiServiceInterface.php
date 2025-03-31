<?php

namespace App\Services;

interface ApiServiceInterface
{
    public function getOffers(int $page): array;
    public function getOfferDetails(string $offerId): array;
    public function getOfferPrices(string $offerId): array;
    public function createHubOffer(array $offerData): void;
}

