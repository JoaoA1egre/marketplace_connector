<?php

namespace App\States;

interface ImportState
{
    public function handle();
    
    public function getStatusMessage(): string;
}
