<?php

namespace App\States;

class FailState implements ImportState
{
    public function handle()
    {
        return "Importação falhou.";
    }

    public function getStatusMessage(): string
    {
        return "A importação não foi concluida com sucesso.";
    }
}
