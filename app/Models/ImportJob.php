<?php

namespace App\Models;

use App\States\{PendingState, ProcessingState, CompletedState, FailState};
use Illuminate\Database\Eloquent\Model;

class ImportJob extends Model
{
    protected $fillable = ['status'];

    public function getState()
    {
        return match ($this->status) {
            'pending' => new PendingState(),
            'processing' => new ProcessingState(),
            'completed' => new CompletedState(),
            'failed' => new FailState(),
            default => throw new \Exception("Estado desconhecido: {$this->status}")
        };
    }

    public function setState(string $newState)
    {
        $this->status = $newState;
        $this->save();
    }
}
