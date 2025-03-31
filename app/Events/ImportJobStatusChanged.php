<?php

namespace App\Events;

use App\Models\ImportJob;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ImportJobStatusChanged
{
    use Dispatchable, SerializesModels;

    public $importJob;

    public function __construct(ImportJob $importJob)
    {
        $this->importJob = $importJob;
    }
}