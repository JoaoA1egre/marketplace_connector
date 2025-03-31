<?php

namespace App\States;

class PendingState implements ImportState
{
    public function handle()
    {
        return "Importação pendente.";
    }

    public function getStatusMessage(): string
    {
        return "A importação está aguardando para ser processada.";
    }
}
