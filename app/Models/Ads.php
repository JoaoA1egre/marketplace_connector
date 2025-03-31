<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ads extends Model
{
    protected $table = 'ads'; // Nome da tabela

    protected $fillable = [
        'title',
        'description',
        'price',
        'marketplace_id',
    ];

    // Se você não quiser que o Eloquent trate o created_at automaticamente
    // (não é necessário nesse caso, mas pode ser útil em algumas situações):
    // public $timestamps = false;
}
