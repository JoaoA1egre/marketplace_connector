<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ImportAdsJob;
use App\Models\ImportJob;

class RunImportAdsJob extends Command
{
    protected $signature = 'job:import-ads';
    protected $description = 'Executa a job ImportAdsJob manualmente';

    public function handle()
    {
        $importJob = ImportJob::create(['status' => 'pending']);
        if (!$importJob) {
            $this->error('Falha ao criar ImportJob.');
            return;
        }
        
        ImportAdsJob::dispatch($importJob); // Executa de forma síncrona, sem fila

        $this->info('Job ImportAdsJob executada com sucesso!');
    }
}
