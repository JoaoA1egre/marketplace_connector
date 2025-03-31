<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\ImportJobStatusChanged;
use App\Listeners\SendStatusChangeNotification;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ImportJobStatusChanged::class => [
            SendStatusChangeNotification::class,
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}
