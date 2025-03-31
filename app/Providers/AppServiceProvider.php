<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ApiServiceInterface;
use App\Services\ApiService;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(ApiServiceInterface::class, ApiService::class);
    }

    public function boot()
    {
        //
    }
}
