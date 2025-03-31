<?php

namespace App\States;

class CompletedState implements ImportState
{
    public function handle()
    {
        return "Importação concluída.";
    }

    public function getStatusMessage(): string
    {
        return "A importação foi concluída com sucesso.";
    }
}
