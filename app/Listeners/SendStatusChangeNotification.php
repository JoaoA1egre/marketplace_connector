<?php

namespace App\Listeners;

use App\Events\ImportJobStatusChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendStatusChangeNotification implements ShouldQueue
{
    public function handle(ImportJobStatusChanged $event)
    {
        $importJob = $event->importJob;
        // Exemplo: Enviar notificação ou salvar log de mudança de estado
        \Log::info("O status do Job de importação foi alterado para: " . $importJob->getCurrentStatusMessage());
    }
}