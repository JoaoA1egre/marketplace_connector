<?php

namespace App\States;

class ProcessingState implements ImportState
{
    public function handle()
    {
        return "Importação em andamento.";
    }

    public function getStatusMessage(): string
    {
        return "A importação está em processamento.";
    }
}
